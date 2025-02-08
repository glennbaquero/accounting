<?php

namespace App\Http\Controllers\PurchasePromissoryNotes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PurchasePromissoryNotes\PromissoryNoteStoreRequest;
use App\Models\PromissoryNotes\PurchasePromissoryNote;
use App\Models\PromissoryNotes\PromissoryNoteAdjustment;
// use App\Models\Customers\CustomerBankRemittance;
use App\Models\AdminSetups\Client;

use DB;

class PromissoryNoteController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('purchase-promissory-notes.index', [
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
        return view('purchase-promissory-notes.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromissoryNoteStoreRequest $request)
    {
        $item = PurchasePromissoryNote::store($request);

        $message = "You have successfully created {$item->promissory_note}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PromissoryNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('purchase-promissory-notes.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PromissoryNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PromissoryNoteStoreRequest $request, $id)
    {
        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->promissory_note}";

        $item = PurchasePromissoryNote::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PromissoryNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->promissory_note}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PromissoryNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->promissory_note}",
        ]);
    }

    public function approve($id, Request $request)
    {
        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $item->update([
            'approved_by_id' => auth()->user()->id,
            'approved_checkbox' => true,
            'approved_date' => now(),
            'stage' => 'Approve',
        ]);

        return response()->json([
            'message' => "You have successfully approved {$item->promissory_note}",
        ]);
    }

    public function redraw($id, Request $request)
    {
        DB::beginTransaction();

        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $adjustment = PromissoryNoteAdjustment::store($request, $item);

        $item->update([
            'stage' => 'Redraw',
        ]);
        
        DB::commit();

        return response()->json([
            'message' => "BOE Adjustment has been created. Please Post the Bills of Exchange.",
        ]);
    }

    public function remit($id, Request $request)
    {
        DB::beginTransaction();

        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $adjustment = PromissoryNoteAdjustment::store($request, $item);
        // $remitance = CustomerBankRemittance::store($request, $item);

        $item->update([
            'stage' => 'Remit',
        ]);
        
        DB::commit();

        return response()->json([
            'message' => "Promissory Note Adjustment and Remittance has been created. Please Post the Promissory Note.",
        ]);
    }

    public function post($id, Request $request)
    {
        DB::beginTransaction();

        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $item->update([
            'posted_by_id' => auth()->user()->id,
            'posted_checkbox' => true,
            'posted_date' => now(),
        ]);

        DB::commit();

        return response()->json([
            'message' => "You have successfully posted {$item->promissory_note}",
        ]);
    }

    public function settle($id, Request $request)
    {
        $item = PurchasePromissoryNote::withTrashed()->findOrFail($id);
        $item->update([
            'stage' => 'Settle',
        ]);

        return response()->json([
            'message' => "You have successfully settled {$item->promissory_note}",
        ]);
    }
}
