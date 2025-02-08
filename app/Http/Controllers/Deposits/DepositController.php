<?php

namespace App\Http\Controllers\Deposits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Deposits\DepositStoreRequest;
use App\Models\Deposits\Deposit;
use App\Models\AdminSetups\Client;

class DepositController extends Controller
{
    public function index()
    {
        return view('deposits.index', [
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
        return view('deposits.create', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepositStoreRequest $request)
    {
        $item = Deposit::store($request);

        $message = "You have successfully created {$item->deposit_slip_id}";
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
        $item = Deposit::withTrashed()->findOrFail($id);
        $customer = $item->customer ? $item->customer : [];

        return view('deposits.show', [
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
    public function update(DepositStoreRequest $request, $id)
    {
        $item = Deposit::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->deposit_slip_id}";

        $item = Deposit::store($request, $item);

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
        $item = Deposit::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->deposit_slip_id}",
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
        $item = Deposit::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->deposit_slip_id}",
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $item = Deposit::withTrashed()->findOrFail($id);
        $item->markCanceled($request);

        return response()->json([
            'message' => "You have successfully approved {$item->deposit_slip_id}",
        ]);
    }

    public function approve(Request $request, $id)
    {
        $item = Deposit::withTrashed()->findOrFail($id);
        $item->markApproved($request);

        return response()->json([
            'message' => "You have successfully approved {$item->deposit_slip_id}",
        ]);
    }

}
