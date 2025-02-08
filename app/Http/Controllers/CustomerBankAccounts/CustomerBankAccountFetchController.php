<?php

namespace App\Http\Controllers\CustomerBankAccounts;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Customers\CustomerBankAccount;
use App\Models\FinancialDimensions\FinancialDimension;

use App\Models\Users\User;
use App\Bank;

use Carbon\Carbon;

class CustomerBankAccountFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CustomerBankAccount;
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

        if($this->request->filled('customer_account')) {
            $query = $query->where('customer_account', $this->request->customer_account);
        }

        if($this->request->filled('client_id')) {
            $query = $query->where('client_id', $this->request->client_id);
        }

        if(!$this->request->filled('archived')) {
            if($this->request->filled('expired')) {
                $query = $query->whereDate('expiration_date', '<=', $dt);
            }else {
                $query = $query->whereDate('expiration_date', '>=', $dt);
            }
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
                'customer_account' => $item->customer_account,
                'bank_account' => $item->bank_account,
                'name' => $item->name,
                'bank_groups' => $item->bank_groups,
                'active_date' => $item->active_date ? Carbon::parse($item->active_date)->format('m/d/Y') : '---',
                'expiration_date' => $item->expiration_date ? Carbon::parse($item->expiration_date)->format('m/d/Y') : '---',
                'bank_account_status' => $item->renderStatus('label'),
                'bank_account_status_class' => $item->renderStatus('class'),
                'bank_account_number' => $item->bank_account_number,
                'account_holder' => $item->account_holder,
                'bank_account_type' => $item->bank_account_type,
                'routing_number' => $item->routing_number,
                'bank_name' => $item->bank_name,
                'bank_branch' => $item->bank_branch,
                'swift_code' => $item->swift_code,
                'iban' => $item->iban,
                'post_fee_checkbox' => $item->post_fee_checkbox,
                'fee_account' => $item->fee_account,
                'clearing' => $item->clearing,
                'cost_center' => $item->cost_center,
                'department' => $item->department,
                'expense_purpose' => $item->expense_purpose,
                'text_code' => $item->text_code,
                'message_to_bank' => $item->message_to_bank,
                'address' => $item->address,
                'name_of_person' => $item->name_of_person,
                'telephone' => $item->telephone,
                'extension' => $item->extension,
                'pager' => $item->pager,
                'mobile_phone' => $item->mobile_phone,
                'fax' => $item->fax,
                'email' => $item->email,
                'sms' => $item->sms,
                'internet_address' => $item->internet_address,
                'telex_number' => $item->telex_number,
                'client_id' => $item->client_id,
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

        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $banks = Bank::orderBy('bank_name', 'asc')->get();

        if ($id) {
            $item = CustomerBankAccount::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'banks' => $banks,
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
