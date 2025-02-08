<?php

namespace App\Http\Controllers\TransactionPostings;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionPostings\TransactionPostingStoreRequest;
use App\Models\PostingProfile\TransactionPosting;
use App\Models\PostingProfile\TransactionPostingHeader;
use App\Models\Users\User;

class TransactionPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transaction-postings.index', [
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $header = TransactionPostingHeader::find($id);
        return view('transaction-postings.create', [
            'clients' => User::getClients(),
            'header' => $header,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionPostingStoreRequest $request)
    {
        $item = TransactionPosting::store($request);

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
     * @param  \App\TransactionPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = TransactionPosting::withTrashed()->findOrFail($id);
        $header = TransactionPostingHeader::find($item->posting_header_id);

        if($header) {
            $header->module = $header->renderModule();
            $header->document = $header->renderDocument();
        }
        
        return view('transaction-postings.show', [
            'item' => $item,
            'header' => $header,
            'clients' => User::getClients(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransactionPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionPostingStoreRequest $request, $id)
    {
        $item = TransactionPosting::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->posting_profile}";

        $item = TransactionPosting::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransactionPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = TransactionPosting::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->posting_profile}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\TransactionPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = TransactionPosting::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->posting_profile}",
        ]);
    }
}
