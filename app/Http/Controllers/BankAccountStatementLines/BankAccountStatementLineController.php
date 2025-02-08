<?php

namespace App\Http\Controllers\BankAccountStatementLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankAccountStatementLines\BankAccountStatementLineStoreRequest;
use App\Models\BankAccountStatements\BankAccountStatementLine;
use App\Models\BankAccountStatements\BankAccountStatement;
use App\Models\AdminSetups\Client;

class BankAccountStatementLineController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        return view('bank-account-statement-lines.create', [
            'item' => $item,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankAccountStatementLineStoreRequest $request, $id)
    {
        $item = BankAccountStatement::withTrashed()->findOrFail($id);
        $request = $request->merge(['statement_id' => $item->bank_statement_id]);
        $item = BankAccountStatementLine::store($request);

        $message = "You have successfully created {$item->statement_line_id}";
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
        $item = BankAccountStatementLine::withTrashed()->findOrFail($id);

        return view('bank-account-statement-lines.show', [
            'item' => $item,
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
    public function update(BankAccountStatementLineStoreRequest $request, $id)
    {
        $item = BankAccountStatementLine::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->statement_line_id}";

        $item = BankAccountStatementLine::store($request, $item);

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
        $item = BankAccountStatementLine::withTrashed()->findOrFail($id);
        $item->archive($request);

        return response()->json([
            'message' => "You have successfully archived {$item->statement_line_id}",
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
        $item = BankAccountStatementLine::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->statement_line_id}",
        ]);
    }

    public function match(Request $request, $id)
    {
        $request->validate([
            'matched' => ['required', 'boolean'],
        ]);

        $item = BankAccountStatementLine::withTrashed()->findOrFail($id);
        $item->update(['matched_checkbox' => $request->matched]);

        return response()->json([
            'message' => "You have successfully updated {$item->statement_line_id}",
        ]);
    }

}
