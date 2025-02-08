<?php

namespace App\Http\Controllers\BankReconciliationJournals;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\BankReconciliationJournals\BankReconciliationJournal;
use App\Models\BankReconciliations\BankReconciliation;
use App\Models\MainAccounts\MainAccount;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\AdminSetups\ClientBankAccount;
use Carbon\Carbon;

class BankReconciliationJournalFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankReconciliationJournal;
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
                'bank_reconciliation_journal_number' => $item->bank_reconciliation_journal_number,
                'journal_batch_number' => $item->journal_batch_number,
                'journal_name_number' => $item->journal_name_number,
                'journal_name' => $item->journal_name,
                'journal_status' => $item->journal_status,
                'balance_journal' => $item->balance_journal,
                'total_debit_journal' => $item->total_debit_journal,
                'total_credit_journal' => $item->total_credit_journal,
                'approved_date' => $item->approved_date,
                'rejected_by' => $item->renderRejectedBy(),
                'posted_on' => $item->posted_on,
                'log_date' => $item->log_date,
                'reversing_entry_checkbox' => $item->reversing_entry_checkbox,
                'department' => $item->department,
                'in_use_checkbox' => $item->in_use_checkbox,

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


        $main_accounts = MainAccount::all();

        if ($id) {
            $item = BankReconciliationJournal::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $reconciliations = BankReconciliation::all();
        $client_banks = ClientBankAccount::all();

        return response()->json([
            'item' => $item,
            'main_accounts' => $main_accounts,
            'cost_centers' => $cost_centers,
            'expense_purposes' => $expense_purposes,
            'departments' => $departments,
            'reconciliations' => $reconciliations,
            'client_banks' => $client_banks,
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
