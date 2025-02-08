<?php

namespace App\Http\Controllers\BankPostings;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\BankPostings\BankPosting;
use App\Models\MainAccounts\MainAccount;

use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\CashflowTransactions\CashflowTransactionAdjustment;

use Carbon\Carbon;

class BankPostingFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankPosting;
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
                'bank_transaction_posting' => $item->bank_transaction_posting,
                'description' => $item->description,
                'document' => $item->document == '1' ? 'Bank Statement Line' : 'Cash Register Transaction',
                'bank_posting_code_number' => $item->bank_posting_code_number,
                'bank_posting' => $item->bank_posting,
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

        $statement_lines = BankAccountStatementLineAdjustment::whereNotNull('approved_date')->get();
        $cash_registers = CashflowTransactionAdjustment::whereNotNull('approved_date')->get();
        $main_accounts = MainAccount::all();

        if ($id) {
            $item = BankPosting::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'main_accounts' => $main_accounts,
            'statement_lines' => $statement_lines,
            'cash_registers' => $cash_registers,
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
