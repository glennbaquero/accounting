<?php

namespace App\Http\Controllers\VendorPaymentMethods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\VendorPaymentMethods\VendorPaymentMethodStoreRequest;
use App\Models\Users\User;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\AdminSetups\Client;

class VendorPaymentMethodController extends Controller
{
    public function index()
    {
        return view('vendor-payment-methods.index', [
            'clients' => Client::where('company_id' , auth()->user()->company_id)->get()
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = VendorPaymentMethod::where('company_id', auth()->user()->company_id)->count() + 1;
        $code = 'vpm-' . date('Ymd') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
        return view('vendor-payment-methods.create',[
            'code' => $code
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorPaymentMethodStoreRequest $request)
    {
        $item = VendorPaymentMethod::store($request);

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
     * @param  \App\VendorPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = VendorPaymentMethod::withTrashed()->findOrFail($id);

        return view('vendor-payment-methods.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(VendorPaymentMethodStoreRequest $request, $id)
    {
        $item = VendorPaymentMethod::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->method_of_payment}";

        $item = VendorPaymentMethod::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = VendorPaymentMethod::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->method_of_payment}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\VendorPaymentMethod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = VendorPaymentMethod::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->method_of_payment}",
        ]);
    }
}
