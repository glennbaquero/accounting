<?php

namespace App\Http\Controllers\Collections;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Collections\CollectionStoreRequest;
use App\Models\Collections\Collection;
use App\Models\AdminSetups\Client;
use App\Models\BillsExchanges\BillsExchange;

class CollectionController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('collections.index', [
            'clients' => $clients,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($boe=null)
    {
        $bill_exchange = [];
        if($boe) {
            $bill_exchange = BillsExchange::where('bills_of_exchange', $boe)->first();
        }
        $clients = Client::all();
        return view('collections.create', [
            'clients' => $clients,
            'boe' => collect($bill_exchange),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionStoreRequest $request)
    {
        $item = Collection::store($request);

        $message = "You have successfully created {$item->collection_id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Collection  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Collection::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('collections.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Collection  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionStoreRequest $request, $id)
    {
        $item = Collection::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->collection_id}";

        $item = Collection::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Collection  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Collection::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->collection_id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Collection  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Collection::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->collection_id}",
        ]);
    }

    public function post(Request $request, $id)
    {
        $item = Collection::withTrashed()->findOrFail($id);
        $item->update([
            'posted_checkbox' => true,
            'posted_date' => now(),
            'posted_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => "You have successfully posted {$item->collection_id}",
        ]);
    }

    public function close(Request $request, $id)
    {
        $item = Collection::withTrashed()->findOrFail($id);
        $item->update([
            'closed_checkbox' => true,
            'closed_date' => now(),
            'closed_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => "You have successfully closed {$item->collection_id}",
        ]);
    }

    public function writeOff(Request $request, $id)
    {
        $item = Collection::withTrashed()->findOrFail($id);
        $item->update([
            'write_off_status' => 'Write Off',
            'write_off_date' => now(),
            'write_off_issued_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => "You have successfully write off {$item->collection_id}",
        ]);
    }
}
