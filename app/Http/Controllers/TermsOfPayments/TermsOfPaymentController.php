<?php

namespace App\Http\Controllers\TermsOfPayments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\JournalSetups\TermsOfPaymentStoreRequest;

use App\Models\JournalSetups\TermsOfPayment;

class TermsOfPaymentController extends Controller
{
   	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('terms.index', [
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
        return view('terms.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TermsOfPaymentStoreRequest $request)
    {
        $item = TermsOfPayment::store($request);

        $message = "You have successfully created {$item->terms_of_payment}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TermsOfPayment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = TermsOfPayment::withTrashed()->findOrFail($id);

        return view('terms.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TermsOfPayment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(TermsOfPaymentStoreRequest $request, $id)
    {
        $item = TermsOfPayment::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->terms_of_payment}";

        $item = TermsOfPayment::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TermsOfPayment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = TermsOfPayment::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->terms_of_payment}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\TermsOfPayment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = TermsOfPayment::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->terms_of_payment}",
        ]);
    }
}
