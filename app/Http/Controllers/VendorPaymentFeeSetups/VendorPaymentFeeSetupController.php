<?php

namespace App\Http\Controllers\VendorPaymentFeeSetups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vendors\VendorPaymentFeeSetup;

use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class VendorPaymentFeeSetupController extends Controller
{
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{
   	    return view('vendor-payment-fee-setups.index', [
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
   	    return view('vendor-payment-fee-setups.create', [
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
   	    $item = VendorPaymentFeeSetup::store($request);

   	    $message = "You have successfully created {$item->nfee_id}";
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
   	    $item = VendorPaymentFeeSetup::withTrashed()->findOrFail($id);

   	    return view('vendor-payment-fee-setups.show', [
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
   	    $item = VendorPaymentFeeSetup::withTrashed()->findOrFail($id);
   	    $message = "You have successfully updated {$item->fee_id}";

   	    $item = VendorPaymentFeeSetup::store($request, $item);

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
   	    $item = VendorPaymentFeeSetup::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully archived {$item->fee_id}",
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
   	    $item = VendorPaymentFeeSetup::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->fee_id}",
   	    ]);
   	}
}
