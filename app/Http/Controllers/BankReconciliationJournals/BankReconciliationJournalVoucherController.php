<?php

namespace App\Http\Controllers\BankReconciliationJournals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankReconciliationJournals\BankReconciliationJournalVoucherStoreRequest;
use App\Models\BankReconciliationJournals\BankReconciliationJournalVoucher;
use App\Models\AdminSetups\Client;

class BankReconciliationJournalVoucherController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankReconciliationJournalVoucherStoreRequest $request)
    {
        $item = BankReconciliationJournalVoucher::store($request);

        $message = "You have successfully created #{$item->id}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankReconciliationJournalVoucher  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = BankReconciliationJournalVoucher::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('payment-reversals.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankReconciliationJournalVoucher  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BankReconciliationJournalVoucherStoreRequest $request, $id)
    {
        $item = BankReconciliationJournalVoucher::withTrashed()->findOrFail($id);
        $message = "You have successfully updated #{$item->id}";

        $item = BankReconciliationJournalVoucher::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankReconciliationJournalVoucher  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BankReconciliationJournalVoucher::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived #{$item->id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BankReconciliationJournalVoucher  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankReconciliationJournalVoucher::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);
    }
}
