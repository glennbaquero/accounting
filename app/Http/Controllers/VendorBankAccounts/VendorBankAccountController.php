<?php

namespace App\Http\Controllers\VendorBankAccounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Vendors\VendorBankAccountStoreRequest;
use App\Models\Users\User;
use App\Models\Vendors\VendorBankAccount;
use App\Models\Vendors\Vendor;
use App\Models\AdminSetups\Client;

class VendorBankAccountController extends Controller
{

    public function index()
    {
        $vendors = Vendor::orderBy('first_name', 'asc')->get()->map(function($vendor) {
            $vendor->createUrl = route('vendor-bank-accounts.create', $vendor->id);
            return $vendor;
        });

        return view('vendor-bank-accounts.index', [
            'clients' => Client::all(),
            'vendors' => $vendors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($vendorid)
    {
        $vendor = Vendor::findOrFail($vendorid);
        return view('vendor-bank-accounts.create', [
            'clients' => Client::all(),
            'vendor' => $vendor,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorBankAccountStoreRequest $request)
    {
        $item = VendorBankAccount::store($request);

        $message = "You have successfully created {$item->vendor_account}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = VendorBankAccount::withTrashed()->findOrFail($id);
        $vendor = $item->vendor ? $item->vendor : [];

        return view('vendor-bank-accounts.show', [
            'item' => $item,
            'vendor' => $vendor,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(VendorBankAccountStoreRequest $request, $id)
    {
        $item = VendorBankAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_name}";

        $item = VendorBankAccount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = VendorBankAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\VendorBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = VendorBankAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_name}",
        ]);
    }
}
