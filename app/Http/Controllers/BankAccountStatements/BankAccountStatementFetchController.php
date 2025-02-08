<?php

namespace App\Http\Controllers\BankAccountStatements;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\BankAccountStatements\BankAccountStatement;
use App\Models\BankAccountTransactions\BankAccountTransaction;
use App\Models\Users\User;

use Carbon\Carbon;

class BankAccountStatementFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankAccountStatement;
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
                'id' => $item->id,
                'client' => $item->client ? $item->client->name : '---',

                'bank_statement_id' => $item->bank_statement_id,
                'client_bank_account_number' => $item->client_bank_account_number,
                'client_bank_account_holder' => $item->client_bank_account_holder,
                'client_bank_branch' => $item->client_bank_branch,

                'approved_date' => $item->approved_date ? Carbon::parse($item->approved_date)->format('m/d/Y h:i A') : '--',
                'bank_statement_issue_date' => $item->bank_statement_issue_date ? Carbon::parse($item->bank_statement_issue_date)->format('m/d/Y h:i A') : '--',

                'currency' => $item->currency,
                'opening_balance' => $item->opening_balance,
                'ending_balance' => $item->ending_balance,
                'total_reconciled' => $item->total_reconciled,
                'total_adjustment' => $item->total_adjustment,
                'total_matched' => $item->total_matched,
                'reconciled_checkbox' => $item->reconciled_checkbox,
                'adjustement_checkbox' => $item->adjustement_checkbox,
                'canceled_checkbox' => $item->canceled_checkbox,

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
        $client_bank_accounts = ClientBankAccount::all();
        $bank_account_transactions = BankAccountTransaction::all();

        if($id) {
            $item = BankAccountStatement::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'client_bank_accounts' => $client_bank_accounts,
            'bank_account_transactions' => $bank_account_transactions,
        ]);
    }

    protected function formatView($item)
    {
        $item->approveUrl = $item->renderApproveUrl();
        $item->cancelUrl = $item->renderCancelUrl();
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->approved_by = $item->renderApprovedUser();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
