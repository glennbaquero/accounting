<?php

namespace App\Http\Controllers\BankAccountTransactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankAccountTransactions\BankAccountTransactionStoreRequest;
use App\Models\BankAccountTransactions\BankAccountTransaction;
use App\Models\AdminSetups\Client;

class BankAccountTransactionController extends Controller
{
    public function index()
    {
        return view('bank-account-transactions.index', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank-account-transactions.create', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankAccountTransactionStoreRequest $request)
    {
        $item = BankAccountTransaction::store($request);

        $message = "You have successfully created {$item->bank_statement}";
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
        $item = BankAccountTransaction::withTrashed()->findOrFail($id);
        $customer = $item->customer ? $item->customer : [];

        return view('bank-account-transactions.show', [
            'item' => $item,
            'customer' => $customer,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BankAccountTransactionStoreRequest $request, $id)
    {
        $item = BankAccountTransaction::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_statement}";

        $item = BankAccountTransaction::store($request, $item);

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
        $item = BankAccountTransaction::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_statement}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankAccountTransaction::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_statement}",
        ]);
    }

    public function cancel($id)
    {
        $item = BankAccountTransaction::withTrashed()->findOrFail($id);
        $item->markCanceled();

        return response()->json([
            'message' => "You have successfully approved {$item->bank_statement}",
        ]);
    }

    public function approve(Request $request, $id)
    {
        $item = BankAccountTransaction::withTrashed()->findOrFail($id);
        $item->markApproved($request);

        return response()->json([
            'message' => "You have successfully approved {$item->bank_statement}",
        ]);
    }

}
