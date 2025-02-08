<?php

namespace App\Http\Controllers\BankReconciliationLines;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\BankReconciliations\BankReconciliationLine;
use App\Models\CashflowTransactions\CashflowTransactionAdjustment;
use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\BankPostings\BankPosting;

use Carbon\Carbon;

class BankReconciliationLineFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankReconciliationLine;
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

        if($this->request->filled('bank_reconciliation_id')) {
            $query = $query->where('bank_reconciliation_id', $this->request->bank_reconciliation_id);
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
                'posted_checkbox' => $item->posted_checkbox,
                'approved_checkbox' => $item->approved_checkbox,
                'description' => $item->description,
                'operation_type' => $item->operation_type,
                'source' => $item->source,
                'statement_adjustment_id' => $item->statement_adjustment_id,
                'cash_register_adjustment_id' => $item->cash_register_adjustment_id,
                'bank_posting_id' => $item->bank_posting ? $item->bank_posting->bank_transaction_posting : $item->bank_posting_id,
                'adjustment_name' => $item->adjustment_name,
                'amount' => $item->amount,
               
                'deleted_at' => $item->deleted_at,
                'alreadyInSelectedItem' => false
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
            'updateUrl' => $item->renderUpdateUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $cash_register_adjustments = CashflowTransactionAdjustment::all();
        $bank_account_statement_adjustments = BankAccountStatementLineAdjustment::all();
        $bank_postings = BankPosting::all();

        if ($id) {
            $item = BankReconciliationLine::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'bank_postings' => $bank_postings,
            'cash_register_adjustments' => $cash_register_adjustments,
            'bank_account_statement_adjustments' => $bank_account_statement_adjustments,
        ]);
    }

    protected function formatView($item)
    {
        $item->approved_by = $item->renderApprovedBy();
        $item->posted_by = $item->renderPostedBy();

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
