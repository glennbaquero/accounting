<?php

namespace App\Http\Controllers\CustomerBankAccounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Customers\CustomerBankAccountStoreRequest;
use App\Models\Users\User;
use App\Models\Customers\CustomerBankAccount;
use App\Models\Customers\Customer;
use App\Models\AdminSetups\Client;

class CustomerBankAccountController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('display_name', 'asc')->get()->map(function($customer) {
            $customer->createUrl = route('customer-bank-accounts.create', $customer->id);
            return $customer;
        });

        return view('customer-bank-accounts.index', [
            'clients' => Client::all(),
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($customerid)
    {
        $customer = Customer::findOrFail($customerid);
        return view('customer-bank-accounts.create', [
            'clients' => Client::all(),
            'customer' => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerBankAccountStoreRequest $request)
    {
        $item = CustomerBankAccount::store($request);

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
     * @param  \App\CustomerBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CustomerBankAccount::withTrashed()->findOrFail($id);
        $customer = $item->customer ? $item->customer : [];

        return view('customer-bank-accounts.show', [
            'item' => $item,
            'customer' => $customer,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerBankAccountStoreRequest $request, $id)
    {
        $item = CustomerBankAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_name}";

        $item = CustomerBankAccount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CustomerBankAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\CustomerBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CustomerBankAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_name}",
        ]);
    }
}
