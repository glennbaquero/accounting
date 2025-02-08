<?php

namespace App\Http\Controllers\Collections;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Collections\Collection;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\CustomerBankAccount;
use App\Models\Customers\Customer;
use App\Models\BillsExchanges\BillsExchange;
use App\Models\Users\User;

use Carbon\Carbon;

class CollectionFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Collection;
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
                'collection_id' => $item->collection_id,
                'collection_date' => $item->collection_date,
                'sent_date' => $item->sent_date,
                'due_date' => $item->due_date,
                'amount_to_settle' => $item->amount_to_settle,
                'customer_account' => $item->customer_account,
                'invoice_account' => $item->invoice_account,
                'invoice_date' => $item->invoice_date,
                'customer_address' => $item->customer_address,
                'customer_name' => $item->customer_name,
                'customer_contact_id' => $item->customer_contact_id,
                'customer_bank_account' => $item->customer_bank_account,
                'client_bank_account' => $item->client_bank_account,
                'description' => $item->description,
                'bills_exchange_id' => $item->bills_exchange_id,
                'bills_exchange_status' => $item->bills_exchange_status,
                'voucher' => $item->voucher,
                'collection_status' => $item->collection_status,
                'activity_type' => $item->activity_type,
                'activity_start_date' => $item->activity_start_date,
                'activity_date' => $item->activity_date,

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
        $client_banks = ClientBankAccount::all();
        $customer_banks = CustomerBankAccount::all();
        $customers = Customer::all();
        $bills_exchanges = BillsExchange::all();

        if ($id) {
            $item = Collection::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'client_banks' => $client_banks,
            'customer_banks' => $customer_banks,
            'customers' => $customers,
            'bills_exchanges' => $bills_exchanges,
            'users' => User::get(),
        ]);
    }

    protected function formatView($item)
    {
        $item->postUrl = $item->renderPostUrl();
        $item->closeUrl = $item->renderCloseUrl();
        $item->writeOffUrl = $item->renderWriteOffUrl();

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->posted_by = $item->renderPostedBy();
        $item->closed_by = $item->renderPostedBy();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
