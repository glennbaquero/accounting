<?php

namespace App\Http\Controllers\InterestSetups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\InterestSetups\InterestSetupStoreRequest;
use App\Models\AdminSetups\InterestSetup;
use App\Models\AdminSetups\Client;

class InterestSetupController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('interest-setups.index', [
            'clients' => $clients,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('interest-setups.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InterestSetupStoreRequest $request)
    {
        $item = InterestSetup::store($request);

        $message = "You have successfully created {$item->interest_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InterestSetup  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = InterestSetup::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('interest-setups.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InterestSetup  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(InterestSetupStoreRequest $request, $id)
    {
        $item = InterestSetup::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->interest_name}";

        $item = InterestSetup::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InterestSetup  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = InterestSetup::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->interest_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\InterestSetup  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = InterestSetup::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->interest_name}",
        ]);
    }
}
