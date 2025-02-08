<?php

namespace App\Http\Controllers\TransactionPostingHeaders;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionPostings\TransactionPostingHeaderStoreRequest;
use App\Models\PostingProfile\TransactionPostingHeader;
use App\Models\Users\User;

class TransactionPostingHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transaction-posting-headers.index', [
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transaction-posting-headers.create', [
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionPostingHeaderStoreRequest $request)
    {
        $item = TransactionPostingHeader::store($request);

        $message = "You have successfully created {$item->posting_profile}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TransactionPostingHeader  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = TransactionPostingHeader::withTrashed()->findOrFail($id);

        return view('transaction-posting-headers.show', [
            'item' => $item,
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransactionPostingHeader  $sampleItem   
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionPostingHeaderStoreRequest $request, $id)
    {
        $item = TransactionPostingHeader::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->posting_profile}";

        $item = TransactionPostingHeader::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransactionPostingHeader  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = TransactionPostingHeader::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->posting_profile}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\TransactionPostingHeader  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = TransactionPostingHeader::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->posting_profile}",
        ]);
    }
}
