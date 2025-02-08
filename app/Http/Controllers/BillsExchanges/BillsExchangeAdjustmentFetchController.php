<?php

namespace App\Http\Controllers\BillsExchanges;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\BillsExchanges\BillsExchangeAdjustment;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\CustomerBankAccount;

use Carbon\Carbon;

class BillsExchangeAdjustmentFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BillsExchangeAdjustment;
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
                'bills_of_exchange' => $item->bills_of_exchange,
                'issue_date' => $item->issue_date,
                'due_from' => $item->due_from,
                'due_to' => $item->due_to,
                'principal_amount' => $item->principal_amount,
                'number_of_times_to_settle' => $item->number_of_times_to_settle,
                'ammount_to_settle' => $item->ammount_to_settle,
                'terms_of_payment' => $item->terms_of_payment,
                'payment_day' => $item->payment_day,
                'interest_rate' => $item->interest_rate,
                'interest_amount' => $item->interest_amount,
                'terms_of_interest' => $item->terms_of_interest,

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
            // 'showUrl' => $item->renderShowUrl(),
            // 'archiveUrl' => $item->renderArchiveUrl(),
            // 'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $client_banks = ClientBankAccount::all();
        $customer_banks = CustomerBankAccount::all();

        if ($id) {
            $item = BillsExchangeAdjustment::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'client_banks' => $client_banks,
            'customer_banks' => $customer_banks,
        ]);
    }

    protected function formatView($item)
    {
        $item->approveUrl = $item->renderApproveUrl();
        $item->redrawUrl = $item->renderRedrawUrl();
        $item->remitUrl = $item->renderRemitUrl();
        $item->postUrl = $item->renderPostUrl();
        $item->settleUrl = $item->renderSettleUrl();

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->approved_by = $item->renderApprovedBy();
        $item->posted_by = $item->renderPostedBy();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
