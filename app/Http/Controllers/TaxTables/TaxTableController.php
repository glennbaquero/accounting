<?php

namespace App\Http\Controllers\TaxTables;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\TaxTables\TaxTableStoreRequest;
use App\Models\TaxTables\TaxTable;
use App\Models\AdminSetups\Client;

class TaxTableController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('tax-tables.index', [
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
        return view('tax-tables.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxTableStoreRequest $request)
    {
        $item = TaxTable::store($request);

        $message = "You have successfully created {$item->tax_posting_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaxTable  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = TaxTable::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('tax-tables.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaxTable  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(TaxTableStoreRequest $request, $id)
    {
        $item = TaxTable::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->tax_posting_name}";

        $item = TaxTable::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaxTable  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = TaxTable::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->tax_posting_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\TaxTable  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = TaxTable::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->tax_posting_name}",
        ]);
    }
}
