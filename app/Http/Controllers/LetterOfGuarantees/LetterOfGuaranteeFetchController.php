<?php

namespace App\Http\Controllers\LetterOfGuarantees;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Letters\LetterOfGuarantee;
use App\Models\Banks\BankFacilityType;
use App\Models\Banks\BankFacilityGroup;
use App\Models\SalesOrders\SalesOrder;
use App\Models\PurchaseOrders\PurchaseOrder;

use Carbon\Carbon;

class LetterOfGuaranteeFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new LetterOfGuarantee;
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
        
        if($this->request->filled('client_id')) {
            $query = $query->where('client_id', $this->request->client_id);
        }

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

                'letter_of_guarantee_number' => $item->letter_of_guarantee_number,
                'requested_by' => $item->requested_by,
                'transaction_type' => $item->transaction_type,
                'received_date' => Carbon::parse($item->received_date)->format('m/d/Y'),
                'issue_date' => Carbon::parse($item->issue_date)->format('m/d/Y'),
                'expiration_date' => Carbon::parse($item->expiration_date)->format('m/d/Y'),
                'amount' => number_format($item->amount, 2, '.', ','),
                'currency' => $item->currency,
                'status' => $item->status,

                'created_at' => $item->renderDate(),
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
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $types = BankFacilityType::where('company_id', auth()->user()->company_id)->get();
        $groups = BankFacilityGroup::where('company_id', auth()->user()->company_id)->get();
        $purchase_orders = SalesOrder::where('company_id', auth()->user()->company_id)->get();
        $sales_orders = PurchaseOrder::where('company_id', auth()->user()->company_id)->get();

        if($id) {
            $item = LetterOfGuarantee::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'types' => $types,
            'groups' => $groups,
            'purchase_orders' => $purchase_orders,
            'sales_orders' => $sales_orders,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->liquidateUrl = $item->renderLiquidateUrl();
        $item->extendUrl = $item->renderExtendUrl();
        $item->approveUrl = $item->renderApproveUrl();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
