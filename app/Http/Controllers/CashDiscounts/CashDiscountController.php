<?php

namespace App\Http\Controllers\CashDiscounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JournalSetups\CashDiscount;
use App\Http\Requests\JournalSetups\CashDiscountStoreRequest;

class CashDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cash-discounts.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cash-discounts.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\JournalSetups\CashDiscount $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashDiscountStoreRequest $request)
    {
        $item = CashDiscount::store($request);

        $message = "You have successfully created {$item->next_discount_code}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JournalSetups\CashDiscount $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CashDiscount::withTrashed()->findOrFail($id);

        return view('cash-discounts.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\JournalSetups\CashDiscount $request
     * @param  \App\Models\JournalSetups\CashDiscount $id 
     * @return \Illuminate\Http\Response
     */
    public function update(CashDiscountStoreRequest $request, $id)
    {
        $item = CashDiscount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->next_discount_code}";

        $item = CashDiscount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JournalSetups\CashDiscount $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CashDiscount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->next_discount_code}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\JournalSetups\CashDiscount $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CashDiscount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->next_discount_code}",
        ]);
    }
}
