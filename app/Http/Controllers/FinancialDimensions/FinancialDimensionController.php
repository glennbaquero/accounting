<?php

namespace App\Http\Controllers\FinancialDimensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\FinancialDimensions\FinancialDimensionStoreRequest;

use App\Models\FinancialDimensions\FinancialDimension;

class FinancialDimensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('financial-dimensions.index', [
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
        return view('financial-dimensions.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\FinancialDimensionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinancialDimensionStoreRequest $request)
    {

        $count = FinancialDimension::withTrashed()->count();
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;
        
        $request['financial_dimension'] = str_pad($count, 4, '0', STR_PAD_LEFT);
        $item = FinancialDimension::store($request);

        $message = "You have successfully created {$item->dimension_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FinancialDimension  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = FinancialDimension::withTrashed()->findOrFail($id);

        return view('financial-dimensions.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\FinancialDimensionStoreRequest  $request
     * @param  \App\FinancialDimension  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(FinancialDimensionStoreRequest $request, $id)
    {
        $item = FinancialDimension::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->dimension_name}";

        $item = FinancialDimension::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FinancialDimension  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = FinancialDimension::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->dimension_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\FinancialDimension  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = FinancialDimension::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->dimension_name}",
        ]);
    }
}
