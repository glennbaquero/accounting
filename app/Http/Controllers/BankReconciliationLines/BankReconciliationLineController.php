<?php

namespace App\Http\Controllers\BankReconciliationLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AdminSetups\Client;
use App\Models\BankReconciliations\BankReconciliation;
use App\Models\BankReconciliations\BankReconciliationLine;

use App\Http\Requests\BankReconciliations\BankReconciliationLineStoreRequest;

use DB;

class BankReconciliationLineController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankReconciliationLineStoreRequest $request)
    {
        $item = BankReconciliationLine::store($request);

        $message = "You have successfully created {$item->name}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankReconciliationLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BankReconciliationLineStoreRequest $request, $id)
    {
        $item = BankReconciliationLine::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        $item = BankReconciliationLine::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankReconciliationLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BankReconciliationLine::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BankReconciliationLine  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankReconciliationLine::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }

}
