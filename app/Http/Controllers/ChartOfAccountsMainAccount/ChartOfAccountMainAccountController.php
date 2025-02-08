<?php

namespace App\Http\Controllers\ChartOfAccountsMainAccountsMainAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LedgerSetup\ChartOfAccountsMainAccountsMainAccount;
use App\Http\Requests\LedgerSetup\ChartOfAccountsMainAccountStoreRequest;
 
class ChartOfAccountsMainAccountMainAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chart-of-accounts-main-account.index', [
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
        $coama = ChartOfAccountsMainAccount::all()->last();
        $coama_id = "1000";
        if ($coama) {
            $coama_id = 100 + (int) $coama->coa_main_account_id;
        }
        return view('chart-of-accounts-main-account.create', [
            'coama_id' => $coama_id
        ]);
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MainAccountCategoryStoreRequest  $request
     * @param  \App\Http\Requests\LedgerSetup\ChartOfAccountsMainAccountStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChartOfAccountsMainAccountStoreRequest $request)
    {
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = ChartOfAccountsMainAccount::store($request);

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
     * @param  \App\Models\LedgerSetup\ChartOfAccountsMainAccount $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = ChartOfAccountsMainAccount::withTrashed()->findOrFail($id);

        return view('chart-of-accounts-main-account.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LedgerSetup\ChartOfAccountsMainAccountStoreRequest $request
     * @param  \App\Models\LedgerSetup\ChartOfAccountsMainAccount $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChartOfAccountsMainAccountStoreRequest $request, $id)
    {
        $item = ChartOfAccountsMainAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->id}";

        $item = ChartOfAccountsMainAccount::store($request, $item);

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
        $item = ChartOfAccountsMainAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->main_account_category}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\LedgerSetup\ChartOfAccountsMainAccount $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = ChartOfAccountsMainAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);
    }
}