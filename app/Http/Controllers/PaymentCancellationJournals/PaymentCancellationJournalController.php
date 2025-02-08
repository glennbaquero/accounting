<?php

namespace App\Http\Controllers\PaymentCancellationJournals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PaymentCancellationJournals\PaymentCancellationJournalStoreRequest;
use App\Models\PaymentCancellationJournals\PaymentCancellationJournal;
use App\Models\AdminSetups\Client;

class PaymentCancellationJournalController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('payment-cancellation-journals.index', [
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
        return view('payment-cancellation-journals.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentCancellationJournalStoreRequest $request)
    {
        $item = PaymentCancellationJournal::store($request);

        $message = "You have successfully created {$item->payment_cancellation_journal_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentCancellationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = PaymentCancellationJournal::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('payment-cancellation-journals.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaymentCancellationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentCancellationJournalStoreRequest $request, $id)
    {
        $item = PaymentCancellationJournal::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->payment_cancellation_journal_number}";

        $item = PaymentCancellationJournal::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaymentCancellationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = PaymentCancellationJournal::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->payment_cancellation_journal_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PaymentCancellationJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PaymentCancellationJournal::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->payment_cancellation_journal_number}",
        ]);
    }
}
