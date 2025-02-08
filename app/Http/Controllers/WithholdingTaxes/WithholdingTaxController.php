<?php

namespace App\Http\Controllers\WithholdingTaxes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\WithholdingTaxes\WithholdingTaxStoreRequest;
use App\Models\WithholdingTaxes\WithholdingTax;
use App\Models\AdminSetups\Client;

class WithholdingTaxController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('withholding-taxes.index', [
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
        return view('withholding-taxes.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WithholdingTaxStoreRequest $request)
    {
        $item = WithholdingTax::store($request);

        $message = "You have successfully created {$item->withholding_tax_posting_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WithholdingTax  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = WithholdingTax::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('withholding-taxes.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WithholdingTax  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(WithholdingTaxStoreRequest $request, $id)
    {
        $item = WithholdingTax::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->withholding_tax_posting_name}";

        $item = WithholdingTax::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WithholdingTax  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = WithholdingTax::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->withholding_tax_posting_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\WithholdingTax  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = WithholdingTax::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->withholding_tax_posting_name}",
        ]);
    }

}
