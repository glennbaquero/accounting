<?php

namespace App\Http\Controllers\TaxTables;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\TaxTables\TaxTableLine;
use App\Models\Procurements\Procurement;
use App\Models\ProductInventories\Products\Product;
use App\Models\Services\Service;
use App\Models\Services\ServiceTask;
use App\Models\MainAccounts\MainAccount;

use Carbon\Carbon;

class TaxTableLineFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new TaxTableLine;
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
                'tax_name' => $item->tax_name,
                'tax_posting_id' => $item->tax_posting_id,
                'tax_posting' => $item->tax_posting,
                'description' => $item->description,
                'level' => $item->level,
                'applied_to' => $item->applied_to,
                'tax_percent' => $item->tax_percent,
                'peza_checkbox' => $item->peza_checkbox,
                'vat_exempt_number_checkbox' => $item->vat_exempt_number_checkbox,
                'major_industry_clasification' => $item->major_industry_clasification,
                'industry_clasification_group' => $item->industry_clasification_group,
                'psic_sections' => $item->psic_sections,
                'psic_divisions' => $item->psic_divisions,
                'psic_groups' => $item->psic_groups,
                'psic_class' => $item->psic_class,
                'psic_subclass' => $item->psic_subclass,
                'created_at' => $item->renderDate('created_at', 'm/d/Y h:i A'),
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
        $products = Product::with('variants')->get();
        $services = Service::all();
        $service_tasks = ServiceTask::all();
        $main_accounts = MainAccount::all();
        $procurements = Procurement::all();

        if ($id) {
            $item = TaxTableLine::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'products' => $products,
            'services' => $services,
            'service_tasks' => $service_tasks,
            'main_accounts' => $main_accounts,
            'procurements' => $procurements,
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
