<?php

namespace App\Http\Controllers\FinancialDimensionValues;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimensionValue;

class FinancialDimensionValueFetchController extends Controller
{
	/**
	 * Set object class of fetched data
	 * 
	 * @return void
	 */
	public function setObjectClass()
	{
	    $this->class = new FinancialDimensionValue;
	}

	/**
	 * Custom filtering of query
	 * 
	 * @param Illuminate\Support\Facades\DB $query
	 * @return Illuminate\Support\Facades\DB $query
	 */
	public function filterQuery($query)
	{
	    /**
	     * Queries
	     * 
	     */
	    
	    if($this->request->filled('financial_dimension')) {
	    	$query = $query->where('financial_dimension', $this->request->financial_dimension);
	    }

	    return $query->withTrashed();
	}

	/**
	 * Custom formatting of data
	 * 
	 * @param Illuminate\Support\Collection $items
	 * @return array $result
	 */
	public function formatData($items)
	{
	    $result = [];

	    foreach($items as $item) {
	        $data = $this->formatItem($item);
	        $data = array_merge($data, [
	            'id' => $item->id,
	            'dimension_name' => $item->dimension_name,
	            'dimension_value_name' => $item->dimension_value_name,
	            'description' => $item->description,
	        ]);

	        array_push($result, $data);
	    }

	    return $result;
	}

	/**
	 * Build array data
	 * 
	 * @param  App\Contracts\AvailablePosition
	 * @return array
	 */
	protected function formatItem($item)
	{
	    return [
	        'showUrl' => $item->renderShowUrl(),
	        'archiveUrl' => $item->renderArchiveUrl(),
	        'restoreUrl' => $item->renderRestoreUrl(),
	    ];
	}

	public function fetchView($id = null)
	{
	    $item = null;

	    if ($id) {
	        $item = FinancialDimensionValue::withTrashed()->findOrFail($id);
	        $item = $this->formatView($item);
	    }

	    return response()->json([
	        'item' => $item,
	    ]);
	}

	protected function formatView($item)
	{
        $item->created_by = $item->created_by_user;
        $item->updated_by = $item->updated_by_user;
        $item->formatted_created_at = $item->renderDate('created_at');
        $item->formatted_updated_at = $item->renderDate('updated_at');
        		
	    $item->archiveUrl = $item->renderArchiveUrl();
	    $item->restoreUrl = $item->renderRestoreUrl();

	    return $item;
	}
}
