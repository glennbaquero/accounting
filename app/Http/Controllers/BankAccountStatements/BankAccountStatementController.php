<?php

namespace App\Http\Controllers\BankAccountStatements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankAccountStatements\BankAccountStatementStoreRequest;
use App\Models\BankAccountStatements\BankAccountStatement;
use App\Models\AdminSetups\Client;

class BankAccountStatementController extends Controller
{
    public function index()
    {
        return view('bank-account-statements.index', [
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
        return view('bank-account-statements.create', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankAccountStatementStoreRequest $request)
    {
        $item = BankAccountStatement::store($request);

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
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        $customer = $item->customer ? $item->customer : [];

        return view('bank-account-statements.show', [
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
    public function update(BankAccountStatementStoreRequest $request, $id)
    {
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_statement}";

        $item = BankAccountStatement::store($request, $item);

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
    public function archive(Request $request, $id)
    {
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        $item->archive($request);

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
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_statement}",
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        $item->markCanceled($request);

        return response()->json([
            'message' => "You have successfully approved {$item->bank_statement}",
        ]);
    }

    public function approve(Request $request, $id)
    {
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        $item->markApproved($request);

        return response()->json([
            'message' => "You have successfully approved {$item->bank_statement}",
        ]);
    }

}
