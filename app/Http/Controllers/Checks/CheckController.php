<?php

namespace App\Http\Controllers\Checks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Checks\CheckStoreRequest;
use App\Models\Checks\Check;
use App\Models\AdminSetups\Client;

class CheckController extends Controller
{
    public function index()
    {
        return view('checks.index', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('checks.create', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckStoreRequest $request)
    {
        $item = Check::store($request);

        $message = "You have successfully created {$item->check_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Check::withTrashed()->findOrFail($id);
        $customer = $item->customer ? $item->customer : [];

        return view('checks.show', [
            'item' => $item,
            'customer' => $customer,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CheckStoreRequest $request, $id)
    {
        $item = Check::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->check_number}";

        $item = Check::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Check::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->check_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Check::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->check_number}",
        ]);
    }

    public function cancel($id)
    {
        $item = Check::withTrashed()->findOrFail($id);

        $item->update([
            'is_cancelled' => true,
            'cancelled_on' => now(),
            'cancelled_by' => auth()->user()->id,            
        ]);

        $item->markCanceled();

        return response()->json([
            'message' => "You have successfully cancelled {$item->check_number}",
        ]);
    }

    public function approve(Request $request, $id)
    {
        $item = Check::withTrashed()->findOrFail($id);
        $item->markApproved($request);

        return response()->json([
            'message' => "You have successfully approved {$item->check_number}",
        ]);
    }
}
