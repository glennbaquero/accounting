<?php

namespace App\Http\Controllers\TaxTables;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\TaxTables\TaxTableLineStoreRequest;
use App\Models\TaxTables\TaxTableLine;
use App\Models\AdminSetups\Client;

class TaxTableLineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxTableLineStoreRequest $request)
    {
        $item = TaxTableLine::store($request);

        $message = "You have successfully created #{$item->tax_name}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaxTableLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(TaxTableLineStoreRequest $request, $id)
    {
        $item = TaxTableLine::withTrashed()->findOrFail($id);
        $message = "You have successfully updated #{$item->tax_name}";

        $item = TaxTableLine::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaxTableLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = TaxTableLine::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived #{$item->tax_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\TaxTableLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = TaxTableLine::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->tax_name}",
        ]);
    }
}
