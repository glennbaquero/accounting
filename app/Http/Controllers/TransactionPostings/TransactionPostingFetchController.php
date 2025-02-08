<?php

namespace App\Http\Controllers\TransactionPostings;

use AlterColumnProtestSettlements;
use App\Extenders\Controllers\FetchController as Controller;
use App\Models\BankPostings\BankPosting;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\PostingProfile\TransactionPosting;
use App\Models\Procurements\Procurement;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\Vendors\Vendor;

class TransactionPostingFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new TransactionPosting;
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

        if($this->request->filled('header')) {
            $query = $query->where('posting_header_id', $this->request->header);
        }

        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        return $query->where('company_id', auth()->user()->company_id);
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
                'posting_profile' => $item->posting_profile,
                'description' => $item->description,
                'account_code' => $item->account_code,
                'account' => $item->account,
                'group_number' => $item->group_number,
                'summary_account' => $item->summaryAccount ? $item->summaryAccount->main_account_name : '---',
                'settle_account' => $item->settle_account_details->main_account_name ?? '---',
                'sales_tax_prepayments' => $item->sales_tax_prepayments,
                'arrival' => $item->arrival,
                'offset_account' => $item->offset_account_details->main_account_name ?? '---',
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
     * @param  App\Contracts\TransactionPosting
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
        $clients = User::getClients();
        $main_accounts = MainAccount::getCompanyData();
        $vendors = Vendor::getCompanyData();

        if ($id) {
            $item = TransactionPosting::withTrashed()->findOrFail($id);
            $item['group_number'] = json_decode($item->group_number);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'main_accounts' => $main_accounts,
            'vendors' => $vendors,
            'types_of_account' => TransactionPosting::renderTypesOfAccount(),
            'debit_accounts' => TransactionPosting::renderDebitAccount(),
            'credit_accounts' => TransactionPosting::renderCreditAccount(),
            'procurement_postings' => Procurement::getCompanyData(),
            'method_of_payment_vendors' => VendorPaymentMethod::getCompanyData(),
            'method_of_payment_customers' => CustomerPaymentMethod::getCompanyData(),
            'settlement_types' => TransactionPosting::renderSettlementTypes(),
            'bank_postings' => BankPosting::getCompanyData(),
            'journals' => TransactionPosting::renderJournals(),  
            'main_account_types' => TransactionPosting::renderMainAccountTypes(),       
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->updated_by_user ? $item->updated_by_user->renderName() : '---';
        $item->created_by = $item->created_by_user ? $item->created_by_user->renderName() : '---';
        $item->updated_on = $item->updated_on ? $item->updated_on : '---';
        $item->created_on = $item->created_on ? $item->created_on : '---';
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
