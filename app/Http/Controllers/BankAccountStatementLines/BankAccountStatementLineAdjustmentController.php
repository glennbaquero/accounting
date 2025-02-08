<?php

namespace App\Http\Controllers\BankAccountStatementLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankAccountStatementLines\BankAccountStatementLineStoreRequest;
use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\BankAccountStatements\BankAccountStatement;
use App\Models\AdminSetups\Client;
use App\Models\BankPostings\BankPosting;

use DB;

class BankAccountStatementLineAdjustmentController extends Controller
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
        $item = BankAccountStatementLineAdjustment::store($request);

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
        $item = BankAccountStatementLineAdjustment::withTrashed()->findOrFail($id);

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
        $item = BankAccountStatementLineAdjustment::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->statement_line_id}";

        $item = BankAccountStatementLineAdjustment::store($request, $item);

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
        $item = BankAccountStatementLineAdjustment::withTrashed()->findOrFail($id);
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
        $item = BankAccountStatementLineAdjustment::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->statement_line_id}",
        ]);
    }

    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        $item = BankAccountStatementLineAdjustment::withTrashed()->findOrFail($id);
        $item->update([
            'approved_date' => now(),
            'approved_by' => $request->user()->id,
        ]);

        $bank_posting = BankPosting::firstOrCreate([
            'bank_statement_line_adjustment_id' => $item->id,
        ], [
            'client_id' => $item->client_id,
            'bank_transaction_posting' => 'Approved Bank Statement Line Adjustment - ' . $item->bank_statement_adjustment_id,
            'description' => $item->description,
            'document' => '1',
            'company_id' => $request->user()->company_id,
            'created_by' => $request->user()->company_id,
            'updated_by' => $request->user()->company_id,
        ]);

        DB::commit();

        return response()->json([
            'message' => "You have successfully approved {$item->statement_line_id}",
        ]);
    }

    public function adjustment(Request $request, $id)
    {
        $request->validate([
            'adjustment' => ['required', 'boolean'],
        ]);

        $item = BankAccountStatementLineAdjustment::withTrashed()->findOrFail($id);
        $item->update(['adjustment_checkbox' => $request->adjustment]);

        return response()->json([
            'message' => "You have successfully updated {$item->statement_line_id}",
        ]);
    }

}
