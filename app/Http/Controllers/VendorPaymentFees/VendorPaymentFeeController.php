<?php

namespace App\Http\Controllers\VendorPaymentFees;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vendors\VendorPaymentFee;

use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class VendorPaymentFeeController extends Controller
{
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{
   	    return view('vendor-payment-fees.index', [
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
   	    return view('vendor-payment-fees.create', [
   	        //
   	    ]);
   	}

   	/**
   	 * Store a newly created resource in storage.
   	 *
   	 * @param  \Illuminate\Http\Request  $request
   	 * @return \Illuminate\Http\Response
   	 */
   	public function store(Request $request)
   	{
   	    $item = VendorPaymentFee::store($request);

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
   	 * @param  \App\Customer  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function show($id)
   	{
   	    $item = VendorPaymentFee::withTrashed()->findOrFail($id);

   	    return view('vendor-payment-fees.show', [
               'item' => $item,
   	    ]);
   	}

   	/**
   	 * Update the specified resource in storage.
   	 *
   	 * @param  \Illuminate\Http\Request  $request
   	 * @param  \App\Customer  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function update(Request $request, $id)
   	{
   	    $item = VendorPaymentFee::withTrashed()->findOrFail($id);
   	    $message = "You have successfully updated {$item->name}";

   	    $item = VendorPaymentFee::store($request, $item);

   	    return response()->json([
   	        'message' => $message,
   	    ]);
   	}

   	/**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\Customer  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = VendorPaymentFee::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully archived {$item->name}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\Customer  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = VendorPaymentFee::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->name}",
   	    ]);
   	}
}
