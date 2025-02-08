<?php

namespace App\Http\Controllers\LinkedMainAccounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\LinkedMainAccounts\LinkedMainAccountStoreRequest;

use App\Models\LedgerSetup\ChartOfAccount;
use App\Models\MainAccounts\MainAccount;

use App\Models\LinkedMainAccounts\LinkedMainAccount;

class LinkedMainAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('linked-main-accounts.index', [
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
        return view('linked-main-accounts.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\LinkedMainAccountStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LinkedMainAccountStoreRequest $request)
    {
        $chart_of_account = ChartOfAccount::where('coa_id', $request->chart_of_accounts_code)->first();
        $main_account = MainAccount::where('main_account_id', $request->main_account)->first();

        $request['chart_of_accounts_name'] = $chart_of_account->coa_name;
        $request['main_account_code'] = $request->main_account;
        $request['main_account_type'] = $main_account->main_account_type;
        $request['main_account_category'] = $main_account->main_account_category_selected ? $main_account->main_account_category_selected->main_account_category : '---';
        $request['main_account'] = $request->main_account;
        $count = LinkedMainAccount::withTrashed()->count();
        $number = str_pad($count, 4, '0', STR_PAD_LEFT);
        $request['linked_main_account_code'] = 'LNKDMNACCT-'.now()->format('m-d-y').'-'.$number;
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = LinkedMainAccount::store($request);

        $message = "You have successfully created {$item->main_account_category}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LinkedMainAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = LinkedMainAccount::withTrashed()->findOrFail($id);

        return view('linked-main-accounts.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\LinkedMainAccountStoreRequest  $request
     * @param  \App\LinkedMainAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(LinkedMainAccountStoreRequest $request, $id)
    {
        $item = LinkedMainAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->linked_main_account_code}";

        $item = LinkedMainAccount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LinkedMainAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = LinkedMainAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->linked_main_account_code}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\LinkedMainAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = LinkedMainAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->linked_main_account_code}",
        ]);
    }


    // client user pivot methods
    public function attachToUser(Request $request, $id) {
        $user = MainAccount::find($request->user)->clients()->attach($id);

        return response()->json([
            'message' => "You have successfully added new client",
        ]);
    }

    public function detachToUser(Request $request, $id) {
        $user = MainAccount::find($request->user)->clients()->detach($id);

        return response()->json([
            'message' => "You have successfully remove the client",
        ]);
    }
}
