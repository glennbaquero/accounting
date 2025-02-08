<?php

namespace App\Http\Controllers\VendorPostingProfiles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Users\User;
use App\Models\PostingProfile\VendorPostingProfile;
use App\Models\AdminSetups\Client;
use App\Models\PostingProfile\VendorPostingProfileHeader;

class VendorPostingProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendor-posting-profiles.index', [
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $header = VendorPostingProfileHeader::find($id);
        return view('vendor-posting-profiles.create', [
            'clients' => User::getClients(),
            'header' => $header,
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
        $item = VendorPostingProfile::store($request);

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
     * @param  \App\VendorPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = VendorPostingProfile::withTrashed()->findOrFail($id);
        $header = VendorPostingProfileHeader::find($item->posting_header_id);
        return view('vendor-posting-profiles.show', [
            'item' => $item,
            'header' => $header,
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = VendorPostingProfile::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->posting_profile}";

        $item = VendorPostingProfile::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = VendorPostingProfile::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->posting_profile}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\VendorPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = VendorPostingProfile::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->posting_profile}",
        ]);
    }
}
