<?php

namespace App\Http\Controllers\WithholdingTaxes;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\WithholdingTaxes\WithholdingTaxLine;

use Carbon\Carbon;

class WithholdingTaxLineFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new WithholdingTaxLine;
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
        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        if($this->request->filled('withholding_tax_posting_id')) {
            $query = $query->where('withholding_tax_posting_id', $this->request->withholding_tax_posting_id);
        }

        $query = $query->where('company_id', auth()->user()->company_id);
        
        return $query;
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
                'withholding_tax_id' => $item->withholding_tax_id,
                'withholding_tax_name' => $item->withholding_tax_name,
                'withholding_tax_posting' => $item->withholding_tax_posting,
                'description' => $item->description,
                'minimum_amount' => $item->minimum_amount,
                'maximum_amount' => $item->maximum_amount,
                'tax_percent' => $item->tax_percent,
                'withholding_tax_exemptions_checkbox' => $item->withholding_tax_exemptions_checkbox,
                'created_at' => $item->renderDate('created_at', 'm/d/Y h:i A'),
                
                'deleted_at' => $item->deleted_at,
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
            'updateUrl' => $item->renderUpdateUrl(),
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        if ($id) {
            $item = WithholdingTaxLine::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
