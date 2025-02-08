<?php

namespace App\Http\Controllers\PaymentSchedules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PaymentSchedules\PaymentScheduleStoreRequest;
use App\Models\PaymentSchedules\PaymentSchedule;
use App\Models\Invoices\CustomerInvoice;
use App\Models\Invoices\VendorInvoice;
use App\Models\AdminSetups\Client;
use App\Models\BillsExchanges\BillsExchange;
use App\Models\Journals\PromissoryNote;

class PaymentScheduleController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('payment-schedules.index', [
            'clients' => $clients,
        ]);
    }

    public function indexPN()
    {
        $clients = Client::all();
        return view('payment-schedules.index-pn', [
            'clients' => $clients,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($customer_invoice_number = null, $bill_exchange_number=null, $type="customer_invoice")
    {
        $invoice = [];
        $bill = [];
        if($customer_invoice_number && $type === 'customer_invoice') {
            $invoice = CustomerInvoice::where('customer_invoice_number', $customer_invoice_number)->first();
        } 

        if($bill_exchange_number && $type === 'bill_exchange') {
            $bill = BillsExchange::where('bills_of_exchange', $bill_exchange_number)->first();
        }

        $clients = Client::all();
        return view('payment-schedules.create', [
            'clients' => $clients,
            'invoice' => collect($invoice),
            'bill' => collect($bill),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPN($vendor_invoice_number = null, $promissory_note_number=null, $type="vendor_invoice")
    {
        $invoice = [];
        $pn = [];
        if($vendor_invoice_number && $type === 'vendor_invoice') {
            $invoice = VendorInvoice::where('vendor_invoice_number', $vendor_invoice_number)->first();
        } 

        if($promissory_note_number && $type === 'pn') {
            $pn = PromissoryNote::where('promissory_note_journal_number', $promissory_note_number)->first();
        }

        $clients = Client::all();
        return view('payment-schedules.create-pn', [
            'clients' => $clients,
            'invoice' => collect($invoice),
            'pn' => collect($pn),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentScheduleStoreRequest $request)
    {
        $item = PaymentSchedule::store($request);

        $message = "You have successfully created {$item->payment_schedule_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentSchedule  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = PaymentSchedule::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('payment-schedules.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaymentSchedule  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentScheduleStoreRequest $request, $id)
    {
        $item = PaymentSchedule::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->payment_schedule_name}";

        $item = PaymentSchedule::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaymentSchedule  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = PaymentSchedule::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->payment_schedule_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PaymentSchedule  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PaymentSchedule::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->payment_schedule_name}",
        ]);
    }

    public function approve(Request $request, $id)
    {
        $item = PaymentSchedule::withTrashed()->findOrFail($id);
        $item->update([
            'approved_checkbox' => true,
            'approved_date' => now(),
            'approved_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => "You have successfully approved {$item->payment_schedule_name}",
        ]);
    }
}
