<?php

namespace App\Http\Controllers\InterestSetups;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\AdminSetups\InterestSetup;
use App\Models\MainAccounts\MainAccount;
use App\Models\TaxTables\TaxTable;

use Carbon\Carbon;

class InterestSetupFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new InterestSetup;
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
     * @param Illuminate\Support\InterestSetup $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'interest_code' => $item->interest_code,
                'interest_name' => $item->interest_name,
                'description' => $item->description,
                'interest_type' => $item->interest_type,
                'grace_period' => $item->grace_period,
                'effective_date' => $item->effective_date,
                'expiration_date' => $item->expiration_date,
                'calculate_interest_every' => $item->calculate_interest_every,
                'interest_earning_debit' => $item->interest_earning_debit,
                'interest_range_by' => $item->interest_range_by,
                'interest_amount' => $item->interest_amount,
                'minimum_interest_amount' => $item->minimum_interest_amount,
                'maximum_interest_amount' => $item->maximum_interest_amount,
                'charge_customer_when_interest_exceeds' => $item->charge_customer_when_interest_exceeds,
                'fee_amount' => $item->fee_amount,
                'fee_account' => $item->fee_account,
                'sales_tax' => $item->sales_tax,
                'interest_payment_credit_account' => $item->interest_payment_credit_account,

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
        $main_accounts = MainAccount::getCompanyData();
        $taxes = TaxTable::getCompanyData();

        if ($id) {
            $item = InterestSetup::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'main_accounts' => $main_accounts,
            'taxes' => $taxes,
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
