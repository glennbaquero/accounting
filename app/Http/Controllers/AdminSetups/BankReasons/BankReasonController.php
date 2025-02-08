<?php

namespace App\Http\Controllers\AdminSetups\BankReasons;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankReasons\BankReasonStoreRequest;
use App\Models\Users\User;
use App\Models\AdminSetups\BankReason;

class BankReasonController extends Controller
{
    public function index()
    {
        return view('bank-reasons.index', [
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
        return view('bank-reasons.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankReasonStoreRequest $request)
    {
        $item = BankReason::store($request);

        $message = "You have successfully created {$item->customer_account}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankReason  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = BankReason::withTrashed()->findOrFail($id);

        return view('bank-reasons.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankReason  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BankReasonStoreRequest $request, $id)
    {
        $item = BankReason::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_name}";

        $item = BankReason::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankReason  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BankReason::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BankReason  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankReason::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_name}",
        ]);
    }
}
