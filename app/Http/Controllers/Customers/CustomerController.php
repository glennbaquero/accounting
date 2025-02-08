<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerStoreRequest;
use App\Models\Customers\Customer;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class CustomerController extends Controller
{
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{
   	    return view('customers.index', [
            'clients' => User::getClients(),
   	    ]);
   	}

   	/**
   	 * Show the form for creating a new resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function create()
   	{
   	    return view('customers.create', [
   	        //
   	    ]);
   	}

   	/**
   	 * Store a newly created resource in storage.
   	 *
   	 * @param  \Illuminate\Http\Request  $request
   	 * @return \Illuminate\Http\Response
   	 */
   	public function store(CustomerStoreRequest $request)
   	{
         $count = Customer::withTrashed()->count();
         $account_num = 'CSTMR-'.now()->format('mdY').'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
   		$request['customer_account'] = $account_num;

   	    $item = Customer::store($request);

   	    $message = "You have successfully created {$item->customer_account}";
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
   	    $item = Customer::withTrashed()->findOrFail($id);

   	    return view('customers.show', [
               'item' => $item,
               'clients' => User::getClients(),
   	    ]);
   	}

   	/**
   	 * Update the specified resource in storage.
   	 *
   	 * @param  \Illuminate\Http\Request  $request
   	 * @param  \App\Customer  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function update(CustomerStoreRequest $request, $id)
   	{
   	    $item = Customer::withTrashed()->findOrFail($id);
   	    $message = "You have successfully updated {$item->customer_account}";

   	    $item = Customer::store($request, $item);

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
   	    $item = Customer::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully archived {$item->customer_account}",
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
   	    $item = Customer::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->customer_account}",
   	    ]);
   	}
}
