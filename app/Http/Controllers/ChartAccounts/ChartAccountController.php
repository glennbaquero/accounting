<?php

namespace App\Http\Controllers\ChartAccounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\ChartAccounts\ChartAccountStoreRequest;

use App\Models\ChartAccounts\ChartAccount;

class ChartAccountController extends Controller
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
        return view('chart-of-accounts.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ChartAccountStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChartAccountStoreRequest $request)
    {

        $count = ChartAccount::withTrashed()->count();
        $request['financial_dimension'] = str_pad($count, 4, '0', STR_PAD_LEFT);
        $item = ChartAccount::store($request);
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;
        
        $message = "You have successfully created {$item->chart_of_accounts_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ChartAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = ChartAccount::withTrashed()->findOrFail($id);

        return view('chart-of-accounts.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ChartAccountStoreRequest  $request
     * @param  \App\ChartAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(ChartAccountStoreRequest $request, $id)
    {
        $item = ChartAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->chart_of_accounts_name}";

        $item = ChartAccount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ChartAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = ChartAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->chart_of_accounts_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\ChartAccount  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = ChartAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->chart_of_accounts_name}",
        ]);
    }
}
