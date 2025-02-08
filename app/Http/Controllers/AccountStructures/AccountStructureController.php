<?php

namespace App\Http\Controllers\AccountStructures;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\AccountStructures\AccountStructureStoreRequest;

use App\Models\AccountStructures\AccountStructure;

use App\Models\Ledgers\Ledger;

use App\Models\LedgerSetup\ChartOfAccount;

class AccountStructureController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account-structures.index', [
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


        $as = AccountStructure::all()->last();
        $as_id = "1000";
        if ($as) {
            $as_id = 100 + (int) $as->ledger_account_structure_id;
        }

        return view('account-structures.create', [
            'as_id' => $as_id
        ]);
    }

    /**
     * Show the form for creating a ledger new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_ledger($ledger_id)
    {
        if(!$ledger_id) {
            return back();
        }

        $ledger_id = Ledger::withTrashed()->where('ledger_id', $ledger_id)->first();        

        $as = AccountStructure::all()->last();
        $as_id = "1000";
        if ($as) {
            $as_id = 100 + (int) $as->ledger_account_structure_id;
        }

        return view('account-structures.create-ledger', [
            'ledger_id' => $ledger_id,
            'as_id' => $as_id
        ]);
    }

    public function create_coa($coa_id)
    {
        if(!$coa_id) {
            return back();
        }

        $coa_id = ChartOfAccount::withTrashed()->where('coa_id', $coa_id)->first();        

        $as = AccountStructure::all()->last();
        $as_id = "1000";
        if ($as) {
            $as_id = 100 + (int) $as->ledger_account_structure_id;
        }

        return view('account-structures.create-coa', [
            'coa_id' => $coa_id,
            'as_id' => $as_id
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\AccountStructureStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountStructureStoreRequest $request)
    {

        // $count = AccountStructure::withTrashed()->count();
        // $request['ledger_account_structure_name'] = str_pad($count, 4, '0', STR_PAD_LEFT);

        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;        
        $item = AccountStructure::store($request);
        $redirect = $item->renderShowUrl();

        $message = "You have successfully created {$item->ledger_account_structure_name}";

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,            
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\AccountStructureStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store_coa(AccountStructureStoreRequest $request)
    {

        // $count = AccountStructure::withTrashed()->count();
        // $request['ledger_account_structure_name'] = str_pad($count, 4, '0', STR_PAD_LEFT);
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;        

        $item = AccountStructure::store($request);
        $redirect = $item->renderShowUrl();

        $message = "You have successfully created {$item->ledger_account_structure_name}";

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,            
            
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AccountStructure  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $item = AccountStructure::withTrashed()->findOrFail($id);
        return view('account-structures.show', [
            'item' => $item,

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AccountStructure  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function showLedger($id)
    {
        $item = AccountStructure::withTrashed()->findOrFail($id);

        return view('account-structures.show-ledger', [
            'item' => $item,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AccountStructure  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function showCoa($id)
    {
        $item = AccountStructure::withTrashed()->findOrFail($id);

        return view('account-structures.show-coa', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\AccountStructureStoreRequest  $request
     * @param  \App\AccountStructure  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(AccountStructureStoreRequest $request, $id)
    {
        $item = AccountStructure::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->ledger_account_structure_name}";

        $item = AccountStructure::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AccountStructure  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = AccountStructure::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->ledger_account_structure_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\AccountStructure  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = AccountStructure::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->ledger_account_structure_name}",
        ]);
    }
}
