<?php

namespace App\Http\Controllers\PaymentSchedules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PaymentSchedules\PaymentScheduleLineStoreRequest;
use App\Models\PaymentSchedules\PaymentScheduleLine;
use App\Models\AdminSetups\Client;

class PaymentScheduleLineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentScheduleLineStoreRequest $request)
    {
        $item = PaymentScheduleLine::store($request);

        $message = "You have successfully created {$item->schedule_line_id}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaymentScheduleLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentScheduleLineStoreRequest $request, $id)
    {
        $item = PaymentScheduleLine::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->schedule_line_id}";

        $item = PaymentScheduleLine::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaymentScheduleLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = PaymentScheduleLine::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->schedule_line_id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PaymentScheduleLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PaymentScheduleLine::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->schedule_line_id}",
        ]);
    }
}
