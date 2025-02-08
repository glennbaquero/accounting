<?php

namespace App\Http\Controllers\ChartOfAccounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\LedgerSetup\ChartOfAccount;
use App\Http\Requests\LedgerSetup\ChartOfAccountStoreRequest;
 
class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chart-of-accounts.index', [
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
        $coa = ChartOfAccount::all()->last();
        $coa_id = "1000";
        if ($coa) {
            $coa_id = 100 + (int) $coa->coa_id;
        }
        return view('chart-of-accounts.create', [
            'coa_id' => $coa_id
        ]);
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MainAccountCategoryStoreRequest  $request
     * @param  \App\Http\Requests\LedgerSetup\ChartOfAccountStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChartOfAccountStoreRequest $request)
    {

        $count = ChartOfAccount::withTrashed()->count();
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;
        
        $item = ChartOfAccount::store($request);

        $message = "You have successfully created #{$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LedgerSetup\ChartOfAccount $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = ChartOfAccount::withTrashed()->findOrFail($id);

        return view('chart-of-accounts.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LedgerSetup\ChartOfAccountStoreRequest $request
     * @param  \App\Models\LedgerSetup\ChartOfAccount $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChartOfAccountStoreRequest $request, $id)
    {
        $item = ChartOfAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->id}";

        $item = ChartOfAccount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = ChartOfAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->main_account_category}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\LedgerSetup\ChartOfAccount $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = ChartOfAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);
    }
}
