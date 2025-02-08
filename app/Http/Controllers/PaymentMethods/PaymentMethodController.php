<?php

namespace App\Http\Controllers\PaymentMethods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\JournalSetups\PaymentMethodStoreRequest;

use App\Models\JournalSetups\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment-methods.index', [
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
        return view('payment-methods.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PaymentMethodStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentMethodStoreRequest $request)
    {
        $item = PaymentMethod::store($request);

        $message = "You have successfully created {$item->name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = PaymentMethod::withTrashed()->findOrFail($id);

        return view('payment-methods.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PaymentMethodStoreRequest  $request
     * @param  \App\PaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethodStoreRequest $request, $id)
    {
        $item = PaymentMethod::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        $item = PaymentMethod::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = PaymentMethod::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PaymentMethod::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }
}
