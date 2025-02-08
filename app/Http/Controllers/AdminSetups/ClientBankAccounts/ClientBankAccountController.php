<?php

namespace App\Http\Controllers\AdminSetups\ClientBankAccounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Clients\ClientBankAccountStoreRequest;
use App\Models\Users\User;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\AdminSetups\Client;

class ClientBankAccountController extends Controller
{
    public function index()
    {
        return view('client-bank-accounts.index', [
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
        return view('client-bank-accounts.create', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientBankAccountStoreRequest $request)
    {
        $item = ClientBankAccount::store($request);

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
     * @param  \App\ClientBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = ClientBankAccount::withTrashed()->findOrFail($id);

        return view('client-bank-accounts.show', [
            'item' => $item,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(ClientBankAccountStoreRequest $request, $id)
    {
        $item = ClientBankAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->bank_name}";

        $item = ClientBankAccount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClientBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = ClientBankAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->bank_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\ClientBankAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = ClientBankAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->bank_name}",
        ]);
    }
}
