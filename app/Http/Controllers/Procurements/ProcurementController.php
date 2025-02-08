<?php

namespace App\Http\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use App\Http\Requests\Procurements\ProcurementStoreRequest;
use App\Models\Users\User;
use App\Models\Procurements\Procurement;
use App\Models\AdminSetups\Client;

class ProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('procurements.index', [
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
        return view('procurements.create', [
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
        $item = Procurement::store($request);

        $message = "You have successfully created {$item->procurement}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Procurement  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Procurement::withTrashed()->findOrFail($id);

        return view('procurements.show', [
            'item' => $item,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Procurement  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Procurement::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->procurement}";

        $item = Procurement::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Procurement  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Procurement::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->procurement}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Procurement  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Procurement::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->procurement}",
        ]);
    }
}
