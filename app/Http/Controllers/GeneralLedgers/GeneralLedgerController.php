<?php

namespace App\Http\Controllers\GeneralLedgers;

use App\Http\Controllers\Controller;

use App\Http\Requests\GeneralLedgers\GeneralLedgerStoreRequest;
use App\Models\GeneralLedgers\ClosingTransaction;
use App\Models\GeneralLedgers\GeneralLedger;
use App\Models\Journals\InvoiceApprovalJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class GeneralLedgerController extends Controller
{
    public function index()
    {
        return view('general-ledgers.index', [
            //
        ]);
    }

    public function create()
    {
        return view('general-ledgers.create', [
            //
        ]);
    }

    public function store(GeneralLedgerStoreRequest $request)
    {
        $item = GeneralLedger::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = GeneralLedger::withTrashed()->findOrFail($id);

        return view('general-ledgers.show', [
            'item' => $item,
        ]);
    }

    public function update(GeneralLedgerStoreRequest $request, $id)
    {
        $item = GeneralLedger::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->id}";

        $item = GeneralLedger::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = GeneralLedger::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->id}",
        ]);
    }

    public function restore($id)
    {
        $item = GeneralLedger::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->id}",
        ]);
    }

    public function generateClosingTransaction($id)
    {
        $item = GeneralLedger::withTrashed()->findOrFail($id);

        if($item) {
            if(!$item->closing_transaction) {

                if(!$item->checkIfEnabledClosingTransaction()) {
                    throw ValidationException::withMessages(['messages' => 'Enable Closing transaction first']);
                }

                $closing_period = $item->getClosingPeriod()->fiscal_period_end_date;
    
                $request = [
                    'company_id' => $item->company_id,
                    'client_id' => $item->client_id,
                    'general_ledger_id' => $item->id,
                    'closing_date' => $closing_period,
                    'closing_period_start' => $item->getClosingPeriodRangeFirstDay(),
                    'closing_period_end' =>  $item->getClosingPeriodRangeLastDay(),
                    'ledger_id' => $item->ledger_id,
                    'created_by' => auth()->user()->id,
                    'prepared_by' => auth()->user()->id,
                    'prepared_on' => now(),
                ];
              
                $closing_transaction = ClosingTransaction::create($request);

                $item->update(['closing_transaction_id' => $closing_transaction->id]);

                return response()->json([
                    'message' => "You have successfully generate closing transaction",
                ]);
    
            }else {
                throw ValidationException::withMessages(['messages' => 'This general ledger is already have a closing transaction']);
            }
        }else {
            throw ValidationException::withMessages(['messages' => 'General ledger not found : closing transaction generation failed']);
        }
    }

    public function approveClosingBalance($id)
    {
        $item = GeneralLedger::withTrashed()->findOrFail($id);

        if($item) {
            if(!$item->checkIfApproveClosingBalace()) {

                $item->update([
                    'approve_closing_balance_date_by' => auth()->user()->id,
                    'approve_closing_balance_date' => now(),
                ]);

                return response()->json([
                    'message' => "You have successfully approve closing balance",
                ]);
            }else {
                throw ValidationException::withMessages(['messages' => 'This general ledger closing balance is already been approved']);
            }
        }else {
            throw ValidationException::withMessages(['messages' => 'General ledger not found : closing balance approval failed']);
        }
    }

    public function enableClosingTransaction($id)
    {
        $item = GeneralLedger::withTrashed()->findOrFail($id);
        
        if($item) {

            if(!$item->checkIfEnabledClosingTransaction()) {

                $item->update([
                    'enabled_closing_by' => auth()->user()->id,
                    'enabled_closing_date' => now(),
                ]);

                return response()->json([
                    'message' => "You have successfully enabled closing transaction",
                ]);

            }else {

                throw ValidationException::withMessages(['messages' => 'This general ledger closing transaction is already been enabled']);

            }
            
        }else {

            throw ValidationException::withMessages(['messages' => 'General ledger not found : closing transaction enabling failed']);

        }

    }

    public function authenticateClosingPassword(Request $request, $id) {
        
        $item = GeneralLedger::withTrashed()->findOrFail($id);

        if($item) {

            if(Hash::check($request->password, $item->closing_transaction->password)) {

                return response()->json([
                    'message' => "Authentication Success",
                ]);

            }else {
                
                throw ValidationException::withMessages(['messages' => 'Wrong Password']);

            }
            
        }else {

            throw ValidationException::withMessages(['messages' => 'General ledger not found']);

        }
        
    }

    public function ArchiveAccountsPayable($id) {

        $general_ledger = GeneralLedger::find($id);

        if($general_ledger) {
            
            try {
                
                DB::beginTransaction();

                $general_ledger->general_ledger_lines()->chunk(200, function($lines)
                {
                    foreach ($lines as $line)
                    {
                        switch ($line->journal_type) {
                            case 'Invoice Approval Journal':
                                
                                if($line->invoice_approval_journal_voucher) {

                                    // Delete all purchase order lines
                                    $purchase_lines = $line->invoice_approval_journal_voucher->vendor_invoice->purchase_order->purchase_order_lines();
      
                                    if($purchase_lines->count() > 0) {
                                      
                                        $purchase_lines->delete();
                            
                                    }
                                    
                                    // Delete all purchase order
                                    $purchase_order = $line->invoice_approval_journal_voucher->vendor_invoice->purchase_order;

                                    if($purchase_order) {

                                        $purchase_order->delete();

                                    }
                                    
                                    // Delete all vendor invoice lines
                                    $vendor_invoice_lines = $line->invoice_approval_journal_voucher->vendor_invoice->vendor_invoice_lines();

                                    if($vendor_invoice_lines->count()) {

                                    $vendor_invoice_lines->delete();

                                    }

                                    // Delete all vendor invoice
                                    $vendor_invoice = $line->invoice_approval_journal_voucher->vendor_invoice;

                                    if($vendor_invoice) {

                                        $vendor_invoice_lines->delete();

                                    }

                                    // Delete all invoice journal approval header
                                    $header = $line->invoice_approval_journal_voucher->header;
                                    
                                    if($header) {
                                       
                                        $header = InvoiceApprovalJournal::find($header->id)->archive();

                                    }

                                    // Delete all invoice journal approval voucher
                                    $invoice_approval_journal_voucher =  $line->invoice_approval_journal_voucher;

                                    if($invoice_approval_journal_voucher) {

                                        $invoice_approval_journal_voucher->delete();

                                    }
                                
                                } 
                                
                                break;
                                
                            case 'Vendor Payment Journal':
                                
                                // Delete  purchase order lines
                                $purchase_lines = $line->vendor_payment_journal_voucher->vendor_payment->vendor_invoice->purchase_order->purchase_order_lines();

                                if($purchase_lines->count() > 0) {

                                    $purchase_lines->delete();

                                }
                                
                                // Delete purchase order
                                $purchase_order = $line->vendor_payment_journal_voucher->vendor_payment->vendor_invoice->purchase_order;

                                if($purchase_order) {

                                    $purchase_order->delete();

                                }
                                
                                // Delete all vendor invoice lines
                                $vendor_invoice_lines = $line->vendor_payment_journal_voucher->vendor_payment->vendor_invoice->vendor_invoice_lines();

                                if($vendor_invoice_lines->count()) {

                                    $vendor_invoice_lines->delete();

                                }

                                // Delete all vendor invoice
                                $vendor_invoice = $line->vendor_payment_journal_voucher->vendor_payment->vendor_invoice;

                                if($vendor_invoice) {

                                    $vendor_invoice->delete();

                                }

                                // Delete all vendor payment lines
                                $vendor_payment_lines = $line->vendor_payment_journal_voucher->vendor_payment->vendor_payment_lines();

                                if($vendor_invoice_lines->count()) {

                                    $vendor_payment_lines->delete();

                                }

                                // Delete all vendor payment
                                $vendor_payment = $line->vendor_payment_journal_voucher->vendor_payment;

                                if($vendor_payment) {

                                    $vendor_payment->delete();

                                }

                                // Delete all invoice journal approval voucher
                                $vendor_payment_journal_voucher =  $line->vendor_payment_journal_voucher;

                                if($vendor_payment_journal_voucher) {

                                    $vendor_payment_journal_voucher->delete();

                                }
                                
                                // Delete all vendor payment journal
                                $header = $line->vendor_payment_journal_voucher->header;

                                if($header) {

                                    $header->delete();

                                }
                                
                                break;
                            
                            default:
                                # code...
                                break;
                        }

                    }

                });

                DB::commit();

                $general_ledger->closing_transaction
                ->update([
                    'archive_payables_on' => now(),
                    'archive_payables_by' => auth()->user()->id
                ]);
                    
                return response()->json([
                    'message' => "Accounts Payable archiving complete",
                ]);

            }catch(Throwable $e) {

                DB::rollback();

                throw ValidationException::withMessages(['messages' => 'achiving failed']);
                
            }
            
        }else {

            throw ValidationException::withMessages(['messages' => 'General ledger not found']);

        }
        
    }

    public function ArchiveAccountsReceivable($id) {

        $general_ledger = GeneralLedger::find($id);

        if($general_ledger) {

            try {

                DB::beginTransaction();

                $general_ledger->general_ledger_lines()->chunk(200, function($lines)
                {
                
                    foreach ($lines as $line)
                    {
                        switch ($line->journal_type) {

                            case 'Customer Invoice Journal':

                                // Delete all sales order lines
                                $sales_order_lines = $line->customer_invoice_journal_voucher->customer_invoice->sale_order->sales_order_lines();

                                if($sales_order_lines->count() > 0) {

                                    $sales_order_lines->delete();

                                }

                                // Delete sales order
                                $sales_order = $line->customer_invoice_journal_voucher->customer_invoice->sales_order;

                                if($sales_order->count() > 0) {

                                    $sales_order->delete();

                                }

                                // Delete all customer invoice lines
                                $customer_invoice_lines = $line->customer_invoice_journal_voucher->customer_invoice->customer_invoice_lines();

                                if($customer_invoice_lines->count() > 0) {

                                    $customer_invoice_lines->delete();

                                }

                                // Delete all customer invoice
                                $customer_invoice_lines = $line->customer_invoice_journal_voucher->customer_invoice;

                                if($customer_invoice_lines->count() > 0) {

                                    $customer_invoice_lines->delete();

                                }


                                // Delete customer invoice journal voucher
                                $customer_invoice_journal_voucher =  $line->customer_invoice_journal_voucher;

                                if($customer_invoice_journal_voucher) {

                                    $customer_invoice_journal_voucher->delete();

                                }
                                    
                                // Delete all vendor payment journal
                                $header = $line->customer_invoice_journal_voucher->header;

                                if($header) {

                                    $header->delete();

                                }
                                
                                
                                break;
                                
                            case 'Customer Payment Journal':
                            
                                    // Delete all customer payment lines
                                    $customer_payment_lines = $line->customer_payment_journal_voucher->customer_payment->customer_payment_lines;

                                    if($customer_payment_lines->count() > 0) {

                                        $customer_payment_lines->delete();

                                    }

                                    // Delete customer payment
                                    $customer_payment = $line->customer_payment_journal_voucher->customer_payment;

                                    if($customer_payment) {

                                        $customer_payment->delete();

                                    }

        
                                    // Delete customer invoice journal voucher
                                    $customer_payment_journal_voucher =  $line->customer_invoice_journal_voucher;

                                    if($customer_payment_journal_voucher) {

                                        $customer_payment_journal_voucher->delete();

                                    }
                                        
                                    // Delete all vendor payment journal
                                    $header = $line->customer_invoice_journal_voucher->header;

                                    if($header) {

                                        $header->delete();

                                    }

                                break;
                            
                            default:
                                # code...
                                break;
                        }

                       
                    }

                });

                $general_ledger->closing_transaction
                ->update([
                    'archive_receivable_on' => now(),
                    'archive_receivable_by' => auth()->user()->id
                ]);

                DB::commit();

                return response()->json([
                    'message' => "Accounts Receivable archiving complete",
                ]);

            }catch(Throwable $e) {

                DB::rollback();

                throw ValidationException::withMessages(['messages' => 'achiving failed']);
                
            }
            
        }else {

            throw ValidationException::withMessages(['messages' => 'General ledger not found']);

        }

    }

    public function ArchiveInventory($id) {

        $general_ledger = GeneralLedger::find($id);

        if($general_ledger) {

            try {

                DB::beginTransaction();

                $general_ledger->general_ledger_lines()->chunk(200, function($lines)
                { 

                });

                $general_ledger->closing_transaction->update([
                    'archive_inventories_on' => now(),
                    'archive_inventories_by' => auth()->user()->id
                ]);

                DB::commit();

                return response()->json([
                    'message' => "Inventories archiving complete",
                ]);

            }catch(Throwable $e) {

                DB::rollback();

                throw ValidationException::withMessages(['messages' => $e]);
                throw ValidationException::withMessages(['messages' => 'achiving failed']);
                
            }
                
                    
        }else {

            throw ValidationException::withMessages(['messages' => 'General ledger not found']);

        }
        
    }

    public function ArchiveCashAndBank($id) {

        $general_ledger = GeneralLedger::find($id);

        if($general_ledger) {

            try {

                DB::beginTransaction();

                $general_ledger->general_ledger_lines()->chunk(200, function($lines)
                { 

                });

                $general_ledger->closing_transaction->update([
                    'archive_cash_and_bank_on' => now(),
                    'archive_cash_and_bank_by' => auth()->user()->id
                ]);

                DB::commit();

                return response()->json([
                    'message' => "Cash and Banks archiving complete",
                ]);

            }catch(Throwable $e) {

                DB::rollback();

                throw ValidationException::withMessages(['messages' => $e]);
                throw ValidationException::withMessages(['messages' => 'achiving failed']);
                
            }
                
                    
        }else {

            throw ValidationException::withMessages(['messages' => 'General ledger not found']);

        }
        
    }

    public function ArchiveGeneralLedger($id) {

        $general_ledger = GeneralLedger::find($id);

        if($general_ledger) {

            try {

                DB::beginTransaction();

                $general_ledger->general_ledger_lines()->chunk(200, function($lines)
                { 

                });

                $general_ledger->closing_transaction->update([
                    'archive_general_ledger_on' => now(),
                    'archive_general_ledger_by' => auth()->user()->id
                ]);

                DB::commit();

                return response()->json([
                    'message' => "General Ledger archiving complete",
                ]);

            }catch(Throwable $e) {

                DB::rollback();

                throw ValidationException::withMessages(['messages' => 'achiving failed']);
                
            }
                
                    
        }else {

            throw ValidationException::withMessages(['messages' => 'General ledger not found']);

        }
        
    }
}
