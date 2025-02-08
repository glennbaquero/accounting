<?php

namespace App\Http\Controllers\PaymentCancellationJournals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PaymentCancellationJournals\PaymentCancellationJournalVoucherStoreRequest;
use App\Models\PaymentCancellationJournals\PaymentCancellationJournalVoucher;
use App\Models\AdminSetups\Client;

class PaymentCancellationJournalVoucherController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentCancellationJournalVoucherStoreRequest $request)
    {
        $item = PaymentCancellationJournalVoucher::store($request);

        $message = "You have successfully created #{$item->id}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaymentCancellationJournalVoucher  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentCancellationJournalVoucherStoreRequest $request, $id)
    {
        $item = PaymentCancellationJournalVoucher::withTrashed()->findOrFail($id);
        $message = "You have successfully updated #{$item->id}";

        $item = PaymentCancellationJournalVoucher::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaymentCancellationJournalVoucher  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = PaymentCancellationJournalVoucher::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived #{$item->id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PaymentCancellationJournalVoucher  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PaymentCancellationJournalVoucher::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);
    }
}
