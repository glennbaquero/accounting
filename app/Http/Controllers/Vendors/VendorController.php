<?php

namespace App\Http\Controllers\Vendors;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Vendors\VendorStoreRequest;
use App\Models\Users\User;
use App\Models\Vendors\Vendor;
use App\Models\AdminSetups\Client;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendors.index', [
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
        return view('vendors.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorStoreRequest $request)
    {
        $request['vendor_account'] = 'vendor-' . date('Ymd') . '-' . str_pad(Vendor::latest()->first()->id ?? 1, 4, '0', STR_PAD_LEFT);

        $item = Vendor::store($request);

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
     * @param  \App\Vendor  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Vendor::withTrashed()->findOrFail($id);

        return view('vendors.show', [
            'item' => $item,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(VendorStoreRequest $request, $id)
    {
        $item = Vendor::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->company_name}";

        $item = Vendor::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Vendor::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->company_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Vendor  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Vendor::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->company_name}",
        ]);
    }
}
