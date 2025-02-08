<?php

namespace App\Http\Controllers\CustomerPaymentMethods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CustomerPaymentMethods\CustomerPaymentMethodStoreRequest;
use App\Models\Users\User;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\AdminSetups\Client;

class CustomerPaymentMethodController extends Controller
{
    public function index()
    {
        return view('customer-payment-methods.index', [
            'clients' => Client::all(),
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer-payment-methods.create', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerPaymentMethodStoreRequest $request)
    {
        $item = CustomerPaymentMethod::store($request);

        $message = "You have successfully created {$item->method_of_payment}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CustomerPaymentMethod::withTrashed()->findOrFail($id);

        return view('customer-payment-methods.show', [
            'item' => $item,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerPaymentMethodStoreRequest $request, $id)
    {
        $item = CustomerPaymentMethod::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->method_of_payment}";

        $item = CustomerPaymentMethod::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CustomerPaymentMethod::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->method_of_payment}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\CustomerPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CustomerPaymentMethod::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->method_of_payment}",
        ]);
    }
}
