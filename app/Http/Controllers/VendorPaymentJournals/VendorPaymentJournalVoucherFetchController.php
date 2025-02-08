<?php

namespace App\Http\Controllers\VendorPaymentJournals;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Journals\VendorPaymentJournal;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\MainAccounts\MainAccount;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use App\Models\Vendors\Vendor;

use Carbon\Carbon;

class VendorPaymentJournalVoucherFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new VendorPaymentJournalVoucher;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        /**
         * Queries
         * 
         */
        if($this->request->filled('id')) {
            $query = $query->where('vendor_payment_journal_number', $this->request->id);
        }

        if($this->request->filled('vendor_invoice_number')) {
            $query = $query->where('invoice_number', $this->request->vendor_invoice_number);
        }

        if($this->request->filled('vendor_account')) {
            $query = $query->where('vendor_account', $this->request->vendor_account);
        }

        if($this->request->filled('status')) {
            $status = $this->request->status;
            if($status == 'approved') {
                $query = $query->whereNotNull('approved_date')->where('vendor_payment_journal_number', $this->request->id);
            }
            if($status == 'rejected') {
                $query = $query->whereNotNull('rejected_by_journal')->where('vendor_payment_journal_number', $this->request->id);
            }
            if($status == 'pending') {
                $query = $query->whereNull('approved_by_journal')->whereNull('approved_date')->where('vendor_payment_journal_number', $this->request->id);
            }
        }
       
        return $query->withTrashed();
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {

            $main_account = null;
            $offset_account = null;
            $normal_balance = 0;
            $increase_rule = null;
            $decrease_rule = null;

            if($item->main_account && $item->main_account != '---') {
                $main_account = MainAccount::withTrashed()->findOrFail($item->main_account);
                $normal_balance = $main_account->balance_control;
                $increase_rule = $main_account->db_cr_requirement;
                $decrease_rule = $main_account->db_cr_proposal;
               
            }else if($item->offset_account_ma && $item->offset_account != '---'){
                
                $offset_account = MainAccount::withTrashed()->findOrFail($item->offset_account);
                
                if($offset_account) {
                    $normal_balance = $offset_account->balance_control;
                    $increase_rule = $offset_account->db_cr_requirement;
                    $decrease_rule = $offset_account->db_cr_proposal;
                }
            }
            
            array_push($result, [
                'client' => $item->client ? $item->client->name : '---',
                'transaction_date' => $item->payment_due_date ? Carbon::parse($item->payment_due_date)->format('m/d/Y') : null,
                'id' => $item->id,
                'entry_pair_number' => $item->entry_pair_number,
                'client_id' => $item->client_id,
                'voucher_number' => $item->voucher_number,
                'vendor_payment_journal_number' => $item->vendor_payment_journal_number,
                'invoice_journal_batch_number' => $item->invoice_journal_batch_number,
                'journal_name' => $item->journal_name,
                'voucher_line_number' => $item->voucher_line_number,
                'voucher_date' => $item->voucher_date ? Carbon::parse($item->voucher_date)->format('m/d/Y') : null,
                'balance_journal' => $item->balance_journal,
                'balance_journal_per_voucher' => $item->balance_journal_per_voucher,
                'total_debit_journal' => $item->total_debit_journal,
                'total_credit_journal' => $item->total_credit_journal,
                'total_debit_per_voucher' => $item->total_debit_per_voucher,
                'total_credit_per_voucher' => $item->total_credit_per_voucher,
                'debit_amount' => $item->debit_amount,
                'credit_amount' => $item->credit_amount,
                'description' => $item->description,
                'approved_date' => $item->approved_date ? Carbon::parse($item->approved_date)->format('m/d/Y') : null,
                'reported_as_ready_by_journal' => $item->reported_as_ready_by_journal,
                'approved_by_journal' => $item->approved_by_journal,
                'rejected_by_journal' => $item->rejected_by_journal,
                'review_date_trans' => $item->review_date_tran,
                'approved_by_id_trans' => $item->approved_by_id_trans,
                'approved_by_name_trans' => $item->approved_by_name_trans,
                'posted_checkbox' => $item->posted_checkbox,
                'posted_on' => $item->posted_on,
                'posted_by' => $item->posted_by_user ? $item->posted_by_user->fullname : null,
                'posting_profile' => $item->posting_profile,
                'vendor_account' => $item->vendor_account,
                'vendor_name' => $item->vendor_name,
                'invoice_number' => $item->invoice_number,
                'invoice_date' => $item->invoice_date ? Carbon::parse($item->invoice_date)->format('m/d/Y') : null,
                'payment_due_date' => $item->payment_due_date ? Carbon::parse($item->payment_due_date)->format('m/d/Y') : null,
                'settlement_type' => $item->settlement_type,
                'method_of_payment' => $item->method_of_payment,
                'terms_of_payment' => $item->terms_of_payment,
                'payment_id' => $item->payment_id,
                'payment_status' => $item->payment_status,
                'payment_specification' => $item->payment_specification,
                'payment_reference' => $item->payment_reference,
                'bank_transaction_type' => $item->bank_transaction_type,
                'bank_account' => $item->bank_account,
                'use_deposit_slip_checkox' => $item->use_deposit_slip_checkox,
                'deposit_slip_number' => $item->deposit_slip_number,
                'payment_reference' => $item->payment_reference,
                'postdated_check_status' => $item->postdated_check_status,
                'check_number' => $item->check_number,
                'check_number_issued' => $item->check_number_issued,
                'maturity_date' => $item->maturity_date ? Carbon::parse($item->maturity_date)->format('m/d/Y') : null,
                'received_date' => $item->received_date ? Carbon::parse($item->maturity_date)->format('m/d/Y') : null,
                'cashier' => $item->cashier,
                'salesperson' => $item->salesperson,
                'issuing_bank_branch' => $item->issuing_bank_branch,
                'issuing_bank_name' => $item->issuing_bank_name,
                'stop_payment' => $item->stop_payment,
                'replacement_check' => $item->replacement_check,
                'original_check' => $item->original_check,
                'check_amount' => $item->check_amount,
                'recipient_name' => $item->recipient_name,
                'main_account' => $item->main_account ? $item->main_account : '---',
                'main_account_name' => isset($main_account->main_account_name) ? $main_account->main_account_name : '---',
                'account_type' => $item->account_type,
                'offset_company_accounts' => $item->offset_company_accounts,
                'offset_account_type' => $item->offset_account_type,
                'offset_account' => $item->offset_account,
                // 'offset_account_name' => $item->offset_account_ma ? $offset_account->main_account_name : '---',
                'offset_account_name' => isset($offset_account->main_account_name) ? $offset_account->main_account_name : '---',
                'offset_account_id' => $item->offset_account,
                'offset_transaction_text' => $item->offset_transaction_text,
                'sales_tax_direction' => $item->sales_tax_direction,
                'sales_tax_group' => $item->sales_tax_group,
                'item_sales_tax_group' => $item->item_sales_tax_group,
                'withholding_tax_group' => $item->withholding_tax_group,
                'fee_account' => $item->fee_account,
                'fee_id' => $item->fee_id,
                'fee_amount' => $item->fee_amount,
                'created_by' => $item->created_by,
                'created_date' =>  $item->created_at ? Carbon::parse($item->created_at)->format('M. d, Y') : '---',
                'updated_date' =>  $item->updated_at ? Carbon::parse($item->updated_at)->format('M. d, Y') : '---',
                'updated_by' => $item->updated_by,
                'log_message' => $item->log_message ?? '---',
                'logged_by' => $item->logged_by ?? '---',
                'log_date' => $item->log_date,
                'alreadyInSelectedItem' => false,

                'normal_balance' => $normal_balance,
                'increase_rule' => $increase_rule,
                'decrease_rule' => $decrease_rule,

                'selected' => false,
                'updateUrl' => $item->updateUrl,
                'posted_by_name' => $item->posted_by_user ? $item->posted_by_user->fullname : null,
                'balance' => $item->balance_journal,
            ]);
        }

        return $result;
    }

    /**
     * Build array data
     * 
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $payment_methods = PaymentMethod::get();
        $terms_of_payments = TermsOfPayment::get();
        $voucher_lines = [];
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');

        $vendors = Vendor::get();
        $main_accounts = MainAccount::get();
        $clients = User::getClients();

        if ($id) {
            $item = VendorPaymentJournal::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);

            $voucher_lines = $item->getVoucherLines();
        }

        return response()->json([
            'item' => $item,
            'payment_methods' => $payment_methods,
            'terms_of_payments' => $terms_of_payments,
            'voucher_lines' => $voucher_lines,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'vendors' => $vendors,
            'main_accounts' => $main_accounts,
            'clients' => $clients
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
