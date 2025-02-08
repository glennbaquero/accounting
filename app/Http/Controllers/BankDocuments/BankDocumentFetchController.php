<?php

namespace App\Http\Controllers\BankDocuments;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\PurchaseOrders\BankDocument;
use App\Models\Vendors\VendorBankAccount;
use App\Models\AdminSetups\ClientBankAccount;

use Carbon\Carbon;

class BankDocumentFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankDocument;
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

                'vendor_bank_account' => $item->vendor_bank_account->bank_account_number,
                'client_bank_account' => $item->client_bank_account->bank_account_number,
                'bank_facility_agreement_number' => $item->bank_facility_agreement_number,
                'bank_facility_type' => $item->bank_facility_type,
                'bank_document_type' => $item->bank_document_type,
                'documentary_credit_type' => $item->documentary_credit_type,
                'documentary_credit_nature' => $item->documentary_credit_nature,
                'beneficiary' => $item->beneficiary,

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
        $vendor_bank_accounts = VendorBankAccount::where('company_id', auth()->user()->company_id)->get();
        $client_bank_accounts = ClientBankAccount::where('company_id', auth()->user()->company_id)->get();

        if($id) {
            $item = BankDocument::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'vendor_bank_accounts' => $vendor_bank_accounts,
            'client_bank_accounts' => $client_bank_accounts,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
