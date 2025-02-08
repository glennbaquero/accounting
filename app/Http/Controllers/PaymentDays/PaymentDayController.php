<?php

namespace App\Http\Controllers\PaymentDays;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\JournalSetups\PaymentDayStoreRequest;
use App\Models\JournalSetups\PaymentDay;

class PaymentDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment-days.index', [
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
        return view('payment-days.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MainAccountCategoryStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentDayStoreRequest $request)
    {
        $item = PaymentDay::store($request);

        $message = "You have successfully created #{$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = PaymentDay::withTrashed()->findOrFail($id);

        return view('payment-days.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MainAccountCategoryStoreRequest  $request
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentDayStoreRequest $request, $id)
    {
        $item = PaymentDay::withTrashed()->findOrFail($id);
        $message = "You have successfully updated #{$item->id}";

        $item = PaymentDay::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = PaymentDay::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->main_account_category}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PaymentDay::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);
    }
}
