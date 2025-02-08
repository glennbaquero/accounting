<?php

namespace App\Http\Controllers\PaymentFees;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentFees\PaymentFee;

use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class PaymentFeeController extends Controller
{
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{
   	    return view('payment-fees.index', [
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
   	    return view('payment-fees.create', [
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
   	    $item = PaymentFee::store($request);

   	    $message = "You have successfully created {$item->fee_id}";
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
   	    $item = PaymentFee::withTrashed()->findOrFail($id);

   	    return view('payment-fees.show', [
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
   	    $item = PaymentFee::withTrashed()->findOrFail($id);
   	    $message = "You have successfully updated {$item->fee_id}";

   	    $item = PaymentFee::store($request, $item);

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
   	    $item = PaymentFee::withTrashed()->findOrFail($id);
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
   	    $item = PaymentFee::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->fee_id}",
   	    ]);
   	}
}
