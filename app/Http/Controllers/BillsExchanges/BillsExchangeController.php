<?php

namespace App\Http\Controllers\BillsExchanges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BillsExchanges\BillsExchangeStoreRequest;
use App\Models\BillsExchanges\BillsExchange;
use App\Models\BillsExchanges\BillsExchangeAdjustment;
use App\Models\Customers\CustomerBankRemittance;
use App\Models\AdminSetups\Client;

use DB;

class BillsExchangeController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('bills-exchanges.index', [
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
        return view('bills-exchanges.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillsExchangeStoreRequest $request)
    {
        $item = BillsExchange::store($request);

        $message = "You have successfully created {$item->bills_of_exchange}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BillsExchange  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = BillsExchange::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('bills-exchanges.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BillsExchange  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BillsExchangeStoreRequest $request, $id)
    {
        $item = BillsExchange::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bills_of_exchange}";

        $item = BillsExchange::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BillsExchange  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BillsExchange::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bills_of_exchange}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BillsExchange  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BillsExchange::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bills_of_exchange}",
        ]);
    }

    public function approve($id, Request $request)
    {
        $item = BillsExchange::withTrashed()->findOrFail($id);
        $item->update([
            'approved_by' => $request->user()->id,
            'approved_checkbox' => true,
            'approved_date' => now(),
            'bills_of_exchange_stage' => 'Approve',
        ]);

        return response()->json([
            'message' => "You have successfully approved {$item->bills_of_exchange}",
        ]);
    }

    public function redraw($id, Request $request)
    {
        DB::beginTransaction();

        $item = BillsExchange::withTrashed()->findOrFail($id);
        $adjustment = BillsExchangeAdjustment::store($request, $item);

        $item->update([
            'bills_of_exchange_stage' => 'Redraw',
        ]);
        
        DB::commit();

        return response()->json([
            'message' => "BOE Adjustment has been created. Please Post the Bills of Exchange.",
        ]);
    }

    public function remit($id, Request $request)
    {
        DB::beginTransaction();

        $item = BillsExchange::withTrashed()->findOrFail($id);
        $adjustment = BillsExchangeAdjustment::store($request, $item);
        $remitance = CustomerBankRemittance::store($request, $item);

        $item->update([
            'bills_of_exchange_stage' => 'Remit',
        ]);
        
        DB::commit();

        return response()->json([
            'message' => "BOE Adjustment and Remittance has been created. Please Post the Bills of Exchange.",
        ]);
    }

    public function post($id, Request $request)
    {
        DB::beginTransaction();

        $item = BillsExchange::withTrashed()->findOrFail($id);
        $item->update([
            'posted_by' => $request->user()->id,
            'posted_checkbox' => true,
            'posted_date' => now(),
        ]);

        DB::commit();

        return response()->json([
            'message' => "You have successfully posted {$item->bills_of_exchange}",
        ]);
    }

    public function settle($id, Request $request)
    {
        $item = BillsExchange::withTrashed()->findOrFail($id);
        $item->update([
            'bills_of_exchange_stage' => 'Settle',
        ]);

        return response()->json([
            'message' => "You have successfully settled {$item->bills_of_exchange}",
        ]);
    }
}
