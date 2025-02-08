<?php

namespace App\Http\Controllers\Charges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Charges\Charge;

use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class ChargeController extends Controller
{
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{
   	    return view('charges.index', [
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
   	    return view('charges.create', [
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
   	    $item = Charge::store($request);

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
   	    $item = Charge::withTrashed()->findOrFail($id);

   	    return view('charges.show', [
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
   	    $item = Charge::withTrashed()->findOrFail($id);
   	    $message = "You have successfully updated {$item->name}";

   	    $item = Charge::store($request, $item);

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
   	    $item = Charge::withTrashed()->findOrFail($id);
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
   	    $item = Charge::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->name}",
   	    ]);
   	}
}
