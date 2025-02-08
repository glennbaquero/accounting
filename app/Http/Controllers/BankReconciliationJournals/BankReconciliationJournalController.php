<?php

namespace App\Http\Controllers\BankReconciliationJournals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankReconciliationJournals\BankReconciliationJournalStoreRequest;
use App\Models\BankReconciliationJournals\BankReconciliationJournal;
use App\Models\AdminSetups\Client;

class BankReconciliationJournalController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('bank-reconciliation-journals.index', [
            'clients' => $clients,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('bank-reconciliation-journals.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankReconciliationJournalStoreRequest $request)
    {
        $item = BankReconciliationJournal::store($request);

        $message = "You have successfully created {$item->bank_reconciliation_journal_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankReconciliationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = BankReconciliationJournal::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('bank-reconciliation-journals.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankReconciliationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BankReconciliationJournalStoreRequest $request, $id)
    {
        $item = BankReconciliationJournal::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_reconciliation_journal_number}";

        $item = BankReconciliationJournal::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankReconciliationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BankReconciliationJournal::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_reconciliation_journal_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BankReconciliationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankReconciliationJournal::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_reconciliation_journal_number}",
        ]);
    }
}
