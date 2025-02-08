<?php

namespace App\Http\Controllers\CustomerSummaries;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customers\CustomerSummary;

use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class CustomerSummaryController extends Controller
{
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{
   	    return view('customer-summaries.index', [
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
   	    return view('customer-summaries.create', [
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
         $request['customer_summary_id'] = uniqid();
   	    $item = CustomerSummary::store($request);

   	    $message = "You have successfully created {$item->customer_summary_id}";
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
   	    $item = CustomerSummary::withTrashed()->findOrFail($id);

   	    return view('customer-summaries.show', [
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
   	    $item = CustomerSummary::withTrashed()->findOrFail($id);
   	    $message = "You have successfully updated {$item->customer_summary_id}";

   	    $item = CustomerSummary::store($request, $item);

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
   	    $item = CustomerSummary::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully archived {$item->customer_summary_id}",
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
   	    $item = CustomerSummary::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->customer_summary_id}",
   	    ]);
   	}

      /**
       * Update the specified resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  \App\Customer  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function approved(Request $request, $id)
      {
          $item = CustomerSummary::withTrashed()->findOrFail($id);
          $message = "You have successfully approved {$item->customer_summary_id}";

          $item->update([
            'approved' => true,
            'approve_date' => now(),
            'approve_by' => auth()->user()->id
          ]);

          return response()->json([
              'message' => $message,
          ]);
      }
}
