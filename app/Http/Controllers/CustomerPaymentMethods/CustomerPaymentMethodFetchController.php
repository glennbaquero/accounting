<?php

namespace App\Http\Controllers\CustomerPaymentMethods;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\MainAccounts\MainAccount;
use App\Models\BankPostings\BankPosting;
use App\Models\Users\User;

use Carbon\Carbon;

class CustomerPaymentMethodFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CustomerPaymentMethod;
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
        // $query = $query->where('company_id', auth()->user()->company_id);
        $dt = now();
        $query = $query->where('company_id', auth()->user()->company_id);

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
                'client' => $item->client ? $item->client->name : '---',

                'method_of_payment_id' => $item->method_of_payment_id,
                'payment_status' => $item->payment_status,
                'postdated_check_status' => $item->postdated_check_status,
                'payment_account' => $item->main_account ? $item->main_account->main_account_name : '---',
                'main_account_id' => $item->main_account_id,
                'postdated_check_clearing_posting' => $item->postdated_check_clearing_posting,
                'bank_posting_profile' => $item->bank_posting_profile,

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

        $mainAccounts = $this->mappedMainAccount();
        $bank_postings = BankPosting::get();

        if ($id) {
            $item = CustomerPaymentMethod::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'mainAccounts' => $mainAccounts,
            'bank_postings' => $bank_postings,
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

    protected function mappedMainAccount() {
        return MainAccount::all()->map(function($item) {
            $item->main_account_category = $item->main_account_category_selected ? $item->main_account_category_selected->main_account_category : '';

            return $item;
        });
    }
}
