<?php

namespace App\Http\Controllers\VendorPostingProfileHeaders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorPostingProfiles\VendorPostingProfileHeaderStoreRequest;
use App\Models\Users\User;
use App\Models\PostingProfile\VendorPostingProfile;
use App\Models\PostingProfile\VendorPostingProfileHeader;

class VendorPostingProfileHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendor-posting-profile-headers.index', [
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
        return view('vendor-posting-profile-headers.create', [
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorPostingProfileHeaderStoreRequest $request)
    {
        $item = VendorPostingProfileHeader::store($request);

        $message = "You have successfully created {$item->posting_profile}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorPostingProfileHeader  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = VendorPostingProfileHeader::withTrashed()->findOrFail($id);

        return view('vendor-posting-profile-headers.show', [
            'item' => $item,
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorPostingProfileHeader  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(VendorPostingProfileHeaderStoreRequest $request, $id)
    {
        $item = VendorPostingProfileHeader::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->posting_profile}";

        $item = VendorPostingProfileHeader::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorPostingProfileHeader  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = VendorPostingProfileHeader::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->posting_profile}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\VendorPostingProfileHeader  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = VendorPostingProfileHeader::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->posting_profile}",
        ]);
    }
}
