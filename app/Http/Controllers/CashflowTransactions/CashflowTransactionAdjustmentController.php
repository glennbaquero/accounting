<?php

namespace App\Http\Controllers\CashflowTransactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CashflowTransactions\CashflowTransactionStoreRequest;
use App\Models\CashflowTransactions\CashflowTransaction;
use App\Models\CashflowTransactions\CashflowTransactionAdjustment;
use App\Models\AdminSetups\Client;
use App\Models\BankPostings\BankPosting;

use DB;

class CashflowTransactionAdjustmentController extends Controller
{
    public function index()
    {
        return view('cashflow-transactions.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cashflow-transactions.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashflowTransactionStoreRequest $request)
    {
        $item = CashflowTransactionAdjustment::store($request);

        $message = "You have successfully created {$item->cashflow_transaction_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CashflowTransactionAdjustment::withTrashed()->findOrFail($id);
        $customer = $item->customer ? $item->customer : [];

        return view('cashflow-transactions.show', [
            'item' => $item,
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CashflowTransactionStoreRequest $request, $id)
    {
        $item = CashflowTransactionAdjustment::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->cashflow_transaction_name}";

        $item = CashflowTransactionAdjustment::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CashflowTransactionAdjustment::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->cashflow_transaction_name}",
        ]);
    }

    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        $item = CashflowTransactionAdjustment::withTrashed()->findOrFail($id);
        $item->update([
            'approved_date' => now(),
            'approved_by' => $request->user()->id,
        ]);

        $bank_posting = BankPosting::firstOrCreate([
            'cash_register_adjustment_id' => $item->id,
        ], [
            'client_id' => $item->client_id,
            'bank_transaction_posting' => 'Approved Cash Registe Adjustment - ' . $item->cashflow_adjustment_id,
            'description' => $item->description,
            'document' => '2',
            'company_id' => $request->user()->company_id,
            'created_by' => $request->user()->company_id,
            'updated_by' => $request->user()->company_id,
        ]);

        DB::commit();

        return response()->json([
            'message' => "You have successfully approved {$item->cashflow_transaction_name}",
        ]);
    }

    public function adjustment(Request $request, $id)
    {
        DB::beginTransaction();

        $request->validate([
            'adjustment' => ['required', 'boolean'],
        ]);

        $item = CashflowTransactionAdjustment::withTrashed()->findOrFail($id);
        $item->update(['adjustment_checkbox' => $request->adjustment]);

        DB::commit();

        return response()->json([
            'message' => "You have successfully updated {$item->cashflow_transaction_name}",
        ]);
    }

}
