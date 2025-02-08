<?php

namespace App\Http\Controllers\CashflowTransactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CashflowTransactions\CashflowTransactionStoreRequest;
use App\Models\CashflowTransactions\CashflowTransaction;
use App\Models\AdminSetups\Client;

class CashflowTransactionController extends Controller
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
        $item = CashflowTransaction::store($request);

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
        $item = CashflowTransaction::withTrashed()->findOrFail($id);
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
        $item = CashflowTransaction::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->cashflow_transaction_name}";

        $item = CashflowTransaction::store($request, $item);

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
        $item = CashflowTransaction::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->cashflow_transaction_name}",
        ]);
    }

    public function match(Request $request, $id)
    {
        $request->validate([
            'matched' => ['required', 'boolean'],
        ]);

        $item = CashflowTransaction::withTrashed()->findOrFail($id);
        $item->update(['matched' => $request->matched]);

        return response()->json([
            'message' => "You have successfully updated {$item->cashflow_transaction_name}",
        ]);
    }

}
