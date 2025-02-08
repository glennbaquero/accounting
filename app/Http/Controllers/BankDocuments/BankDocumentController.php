<?php

namespace App\Http\Controllers\BankDocuments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Checks\CheckStoreRequest;
use App\Models\PurchaseOrders\BankDocument;
use App\Models\AdminSetups\Client;

class BankDocumentController extends Controller
{
    public function index()
    {
        return view('bank-documents.index', [
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
        return view('bank-documents.create', [
            'clients' => Client::all(),
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
        $item = BankDocument::store($request);

        $message = "You have successfully created {$item->bank_facility_agreement_number}";
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
        $item = BankDocument::withTrashed()->findOrFail($id);

        return view('bank-documents.show', [
            'item' => $item,
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
    public function update(Request $request, $id)
    {
        $item = BankDocument::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_facility_agreement_number}";

        $item = BankDocument::store($request, $item);

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
        $item = BankDocument::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_facility_agreement_number}",
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
        $item = BankDocument::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_facility_agreement_number}",
        ]);
    }
}
