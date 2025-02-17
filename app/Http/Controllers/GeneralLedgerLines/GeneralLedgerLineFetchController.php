<?php

namespace App\Http\Controllers\GeneralLedgerLines;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\GeneralLedgers\GeneralLedger;
use App\Models\GeneralLedgers\GeneralLedgerLine;

use Carbon\Carbon;
use Illuminate\Http\Request;

class GeneralLedgerLineFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new GeneralLedgerLine;
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
        
        if($this->request->filled('general_ledger_id')) {
            $query = $query->where('general_ledger_id', $this->request->general_ledger_id);
        }

        if($this->request->filled('line_type')) {
            $query = $query->where('line_type', $this->request->line_type);
        }

        if($this->request->filled('main_account')) {
            $query = $query->where('main_account', $this->request->main_account);
        }

      
        return $query->orderBy('ledger_line_number');
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
      				'ledger_journal_code' => $item->ledger_journal_code,
      				'ledger_journal_line_id' => $item->ledger_journal_line_id,
      				'ledger_line_number' => $item->ledger_line_number,
      				'company_name' => $item->company_name,
      				'company_id' => $item->company_id,
      				'client_id' => $item->client_id,
      				'ledger' => $item->ledger,
      				'ledger_calendar' => $item->ledger_calendar,
      				'ledger_journal_name' => $item->ledger_journal_name,

      				'journal_header_id' => $item->journal_header_id,
      				'journal_voucher_id' => $item->journal_voucher_id,
      				'journal_name' => $item->journal_name,
      				'journal_type' => $item->journal_type,

      				'main_account_code_number' => $item->main_account_code_number,
      				'main_account' => $item->main_account,
      				'main_account_type' => $item->main_account_type,
      				'main_account_category' => $item->main_account_category,
      				'main_account_normal_balance' => $item->main_account_normal_balance,
      				'ledger_transaction_date' => Carbon::parse($item->ledger_transaction_date)->format('M d, Y'),
      				'cost_center' => $item->cost_center,
      				'department' => $item->department,
      				'expense_purpose' => $item->expense_purpose,
      				'matched_voucher_to_gl' => $item->matched_voucher_to_gl,
      				'ledger_journal_line_status' => $item->ledger_journal_line_status,
      				'debit_amount' => $item->debit_amount,
      				'credit_amount' => $item->credit_amount,
      				'balance_amount' => $item->balance_amount,
                    'debit_amount_format' => number_format($item->debit_amount, 2, '.', ','),
                    'credit_amount_format' => number_format($item->credit_amount, 2, '.', ','),
                    'balance_amount_format' => number_format($item->balance_amount, 2, '.', ','),
                    'reversed_checkbox' => $item->reversed_checkbox,
      				'reverse_date' => $item->reverse_date ? Carbon::parse($item->reverse_date)->format('M d, Y') : '---',
      				'reverse_by' => $item->reverse_by,
      				'adjusted_checkbox' => $item->adjusted_checkbox,
      				'adjusting_date' => $item->adjusting_date ? Carbon::parse($item->adjusting_date)->format('M d, Y') : '---',
      				'adjusted_by' => $item->adjusted_by,

      				'posted_checkbox' => $item->posted_checkbox,
      				'posted_on' => $item->posted_on ? Carbon::parse($item->posted_on)->format('M d, Y') : '---',
      				'posted_by' => $item->posted_by,
      				'description' => $item->description,
      				'posted_voucher' => $item->posted_voucher,
      				'created_by' => $item->created_by,
      				'updated_by' => $item->updated_by,
      				'general_ledger_id' => $item->general_ledger_id,
      				'journal_line_id' => $item->journal_line_id,
              
                    'created_at' => $item->renderDate(),
                    'deleted_at' => $item->deleted_at,

                    'main_account_name' => $item->main_account_relation->main_account_name,
                    'main_account_category_name' => $item->main_account_relation->main_account_category_selected->main_account_category,
                    'formatted_ledger_transaction_date' => $item->ledger_transaction_date ? Carbon::parse($item->ledger_transaction_date)->format('M d, Y') : '---',
            ]);

            array_push($result, $data);
        }

        return $result;
    }

    protected function formatItem($item)
    {
        return [
            // 'showUrl' => $item->renderShowUrl(),
            // 'archiveUrl' => $item->renderArchiveUrl(),
            // 'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        if ($id) {
            $item = GeneralLedgerLine::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }

    public function fetchGeneraLedger(Request $request) {
        $gl = GeneralLedger::find($request->id);
        
        // get distinct main account types
        $types = $types = $gl->ledger?->chart_of_account?->main_accounts->unique('main_account_type')->pluck('main_account_type');

        $query = $gl->general_ledger_lines->groupBy('main_account_relation.main_account_name')->map(function ($row) {
             return [ 
                'main_account_type' => $row->unique('main_account_type')->pluck('main_account_type')->first(), 
                'name' => key($row->groupBy('main_account_relation.main_account_name')->toArray()),
                'total_debit' => $row->sum('debit_amount'), 
                'total_credit' => $row->sum('credit_amount'),
                'total' =>  $row->sum('credit_amount') -  $row->sum('debit_amount'),
                'row_type' => 'data'
            ];
        });

        $stucture = [];
        
        foreach ($types as $key => $type) {
           $temps = $query->where('main_account_type', $type);
           array_push($stucture, ['row_type' => 'title', 'type' => $type]);
           foreach ($temps as $key => $temp) {
                array_push($stucture, $temp);
           }

           array_push($stucture, ['row_type' => 'total', 'type' => $type , 'total' => $temps->sum('total_credit') - $temps->sum('total_debit')]);
        }

        return ['items' => $stucture, 'opening_balance' => $gl->getOpeningBalance()];
    }

    public function fetchAdjustedTrialBalance(Request $request) {
        $gl = GeneralLedger::find($request->id);
        
        $query = $gl->general_ledger_lines->groupBy('main_account_relation.main_account_name')->map(function ($row) {
             return [ 
                'main_account_type' => $row->unique('main_account_type')->pluck('main_account_type')->first(), 
                'name' => key($row->groupBy('main_account_relation.main_account_name')->toArray()),
                'total_debit' => $row->sum('debit_amount'), 
                'total_credit' => $row->sum('credit_amount'),
                'total' => $row->sum('credit_amount') - $row->sum('debit_amount'),
            ];
        });

        $grand_total = $query->sum('total');
        $stucture = [];
        
        foreach ($query as $key => $type) {
            array_push($stucture, $type);
        }

        array_push($stucture, ['total' => $grand_total]);
        
        return $stucture;
    }

    public function fetchUnadjustedTrialBalance(Request $request) {
        $gl = GeneralLedger::find($request->id);
        
        $query = $gl->general_ledger_lines()->doesntHave('accrual_posting')->get()->groupBy('main_account_relation.main_account_name')->map(function ($row) {

             return [ 
                'main_account_type' => $row->unique('main_account_type')->pluck('main_account_type')->first(), 
                'name' => key($row->groupBy('main_account_relation.main_account_name')->toArray()),
                'total_debit' => $row->sum('debit_amount'), 
                'total_credit' => $row->sum('credit_amount'),
                'total' => $row->sum('credit_amount') - $row->sum('debit_amount'),
            ];
        });

        $grand_total = $query->sum('total');
        $stucture = [];
        
        foreach ($query as $key => $type) {
            array_push($stucture, $type);
        }

        array_push($stucture, ['total' => $grand_total]);
        
        return $stucture;
    }

    public function fetchPostClosingTrialBalance(Request $request) {
        $gl = GeneralLedger::find($request->id);
        
        $query = $gl->general_ledger_lines()->whereHas('main_account_relation', function ($query) {
             return $query->where('main_account_type', 'Asset')
            ->orWhere('main_account_type', 'Liability')
            ->orWhere('main_account_type', 'Equity'); })
            ->doesntHave('accrual_posting')->get()
            ->groupBy('main_account_relation.main_account_name')->map(function ($row) {

             return [ 
                'main_account_type' => $row->unique('main_account_type')->pluck('main_account_type')->first(), 
                'name' => key($row->groupBy('main_account_relation.main_account_name')->toArray()),
                'total_debit' => $row->sum('debit_amount'), 
                'total_credit' => $row->sum('credit_amount'),
                'total' => $row->sum('credit_amount') - $row->sum('debit_amount'),
            ];
        });

        $grand_total = $query->sum('total');
        $stucture = [];
        
        foreach ($query as $key => $type) {
            array_push($stucture, $type);
        }

        array_push($stucture, ['total' => $grand_total]);
        
        return $stucture;
    }

}
