<?php

namespace App\Http\Controllers\FinancialDimensionValues;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\FinancialDimensionValues\FinancialDimensionValueStoreRequest;

use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\FinancialDimensions\FinancialDimension;

class FinancialDimensionValueController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('financial-dimension-values.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($financial_dimension)
    {


        $fdv = FinancialDimensionValue::all()->last();
        $fdv_id = "1000";
        if ($fdv) {
            $fdv_id = 100 + (int) $fdv->financial_dimension_value_code;
        }

    	if(!$financial_dimension) {
    		return back();
    	}

    	$fd = FinancialDimension::withTrashed()->where('financial_dimension', $financial_dimension)->first();

        return view('financial-dimension-values.create', [
            'financial_dimension' => $fd,
            'fdv_id' => $fdv_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\FinancialDimensionValueStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinancialDimensionValueStoreRequest $request)
    {

        // $count = FinancialDimensionValue::withTrashed()->count();
        // $request['financial_dimension_value_code'] = str_pad($count, 4, '0', STR_PAD_LEFT);

        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;
        $item = FinancialDimensionValue::store($request);


        $message = "You have successfully created {$item->financial_dimension_value_code}";
        $redirect = route('financial-dimensions.show', $item->parent->id);

        dd((request()->is('redirect')) ? 'active' : '');

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FinancialDimensionValue  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = FinancialDimensionValue::withTrashed()->findOrFail($id);
		$fd = FinancialDimension::withTrashed()->where('financial_dimension', $item->financial_dimension)->first();
        return view('financial-dimension-values.show', [
            'item' => $item,
            'financial_dimension' => $fd,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\FinancialDimensionValueStoreRequest  $request
     * @param  \App\FinancialDimensionValue  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(FinancialDimensionValueStoreRequest $request, $id)
    {
        $item = FinancialDimensionValue::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->financial_dimension_value_code}";

        $item = FinancialDimensionValue::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FinancialDimensionValue  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = FinancialDimensionValue::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->financial_dimension_value_code}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\FinancialDimensionValue  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = FinancialDimensionValue::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->financial_dimension_value_code}",
        ]);
    }
}
