<?php

namespace App\Http\Controllers\LetterCreditPurchases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Checks\CheckStoreRequest;
use App\Models\PurchaseOrders\LetterCreditPurchase;
use App\Models\AdminSetups\Client;

class LetterCreditPurchaseController extends Controller
{
    public function index()
    {
        return view('letter-credit-purchases.index', [
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
        return view('letter-credit-purchases.create', [
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
        $item = LetterCreditPurchase::store($request);

        $message = "You have successfully created {$item->bank_document_number}";
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
        $item = LetterCreditPurchase::withTrashed()->findOrFail($id);

        return view('letter-credit-purchases.show', [
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
        $item = LetterCreditPurchase::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_document_number}";

        $item = LetterCreditPurchase::store($request, $item);

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
        $item = LetterCreditPurchase::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_document_number}",
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
        $item = LetterCreditPurchase::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_document_number}",
        ]);
    }

    public function close($id)
    {
        $item = LetterCreditPurchase::withTrashed()->findOrFail($id);

        $item->update([
            'is_close' => true,
            'close' => now(),
            'close_by' => auth()->user()->id,          
            'purchase_status' => 'Closed',                
        ]);

        return response()->json([
            'message' => "You have successfully closed {$item->bank_document_number}",
        ]);
    }

    public function confirm(Request $request, $id)
    {
        $item = LetterCreditPurchase::withTrashed()->findOrFail($id);

        $item->update([
            'is_confirmed' => true,
            'confirmed' => now(),
            'confirmed_by' => auth()->user()->id,            
            'purchase_status' => 'Confirmed',            
        ]);

        return response()->json([
            'message' => "You have successfully confirmed {$item->bank_document_number}",
        ]);
    }

    public function amendment(Request $request, $id)
    {
        $item = LetterCreditPurchase::withTrashed()->findOrFail($id);

        $item->update([
            'amendment_number' => $item->amendment_number + 1,
            'amendment_on' => now(),
            'amendment_by' => auth()->user()->id,            
        ]);

        return response()->json([
            'message' => "You have successfully confirmed {$item->bank_document_number}",
        ]);
    }
}
