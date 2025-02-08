<?php

namespace App\Http\Controllers\InterestCalculations;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\InterestCalculations\InterestCalculation;
use App\Models\PostingProfile\CustomerPostingProfile;
use App\Models\Customers\Customer;
use App\Models\Customers\CustomerBankAccount;
use App\Models\BillsExchanges\BillsExchange;

use Carbon\Carbon;

class InterestCalculationFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new InterestCalculation;
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
     * @param Illuminate\Support\InterestCalculation $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'from_date' => $item->from_date,
                'to_date' => $item->to_date,
                'round_off' => $item->round_off,
                'invoice' => $item->invoice,
                'credit_note' => $item->credit_note,
                'payment' => $item->payment,
                'interest' => $item->interest,
                
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
        $posting_profiles = CustomerPostingProfile::getCompanyData();
        $customers = Customer::all();
        $bills_exchanges = BillsExchange::getCompanyData();
        $customer_banks = CustomerBankAccount::getCompanyData();

        if ($id) {
            $item = InterestCalculation::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'posting_profiles' => $posting_profiles,
            'customers' => $customers,
            'bills_exchanges' => $bills_exchanges,
            'customer_banks' => $customer_banks,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->generateInterestNoteUrl = route('interest-notes.create');
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
