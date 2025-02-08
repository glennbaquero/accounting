<?php

namespace App\Http\Controllers\CustomerPostingProfiles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use App\Http\Requests\PostingProfiles\CustomerPostingProfileStoreRequest;
use App\Models\Users\User;
use App\Models\PostingProfile\CustomerPostingProfile;
use App\Models\PostingProfile\CustomerPostingProfileHeader;
use App\Models\AdminSetups\Client;

class CustomerPostingProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer-posting-profiles.index', [
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
        return view('customer-posting-profiles.create', [
            'clients' => User::getClients(),
            'header' => CustomerPostingProfileHeader::find($id),
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
        $item = CustomerPostingProfile::store($request);

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
     * @param  \App\CustomerPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CustomerPostingProfile::withTrashed()->findOrFail($id);

        return view('customer-posting-profiles.show', [
            'item' => $item,
            'clients' => User::getClients(),
            'header' => CustomerPostingProfileHeader::find($item->posting_header_id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = CustomerPostingProfile::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->posting_profile}";

        $item = CustomerPostingProfile::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CustomerPostingProfile::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->posting_profile}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\CustomerPostingProfile  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CustomerPostingProfile::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->posting_profile}",
        ]);
    }
}
