<?php

namespace App\Http\Controllers\BankPostings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BankPostings\BankPostingStoreRequest;
use App\Models\BankPostings\BankPosting;
use App\Models\AdminSetups\Client;

class BankPostingController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('bank-postings.index', [
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
        return view('bank-postings.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankPostingStoreRequest $request)
    {
        $item = BankPosting::store($request);

        $message = "You have successfully created {$item->bank_transaction_posting}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = BankPosting::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('bank-postings.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BankPostingStoreRequest $request, $id)
    {
        $item = BankPosting::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_transaction_posting}";

        $item = BankPosting::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BankPosting::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_transaction_posting}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BankPosting  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankPosting::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_transaction_posting}",
        ]);
    }
}
