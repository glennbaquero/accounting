<?php

namespace App\Http\Controllers\CostCenters;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\JournalSetups\CostCenterStoreRequest;

use App\Models\JournalSetups\CostCenter;

class CostCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cost-centers.index', [
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
        return view('cost-centers.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostCenterStoreRequest $request)
    {
        $item = CostCenter::store($request);

        $message = "You have successfully created {$item->item_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CostCenter  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CostCenter::withTrashed()->findOrFail($id);

        return view('cost-centers.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CostCenter  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CostCenterStoreRequest $request, $id)
    {
        $item = CostCenter::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->item_number}";

        $item = CostCenter::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CostCenter  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CostCenter::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->item_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\CostCenter  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CostCenter::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->item_number}",
        ]);
    }
}
