<?php

namespace App\Http\Controllers\WithholdingTaxes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\WithholdingTaxes\WithholdingTaxLineStoreRequest;
use App\Models\WithholdingTaxes\WithholdingTaxLine;
use App\Models\AdminSetups\Client;

class WithholdingTaxLineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WithholdingTaxLineStoreRequest $request)
    {
        $item = WithholdingTaxLine::store($request);

        $message = "You have successfully created {$item->withholding_tax_id}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WithholdingTaxLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(WithholdingTaxLineLineStoreRequest $request, $id)
    {
        $item = WithholdingTaxLine::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->withholding_tax_id}";

        $item = WithholdingTaxLine::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WithholdingTaxLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = WithholdingTaxLine::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->withholding_tax_id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\WithholdingTaxLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = WithholdingTax::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->withholding_tax_id}",
        ]);
    }
}
