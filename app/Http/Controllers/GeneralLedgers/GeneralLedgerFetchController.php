<?php

namespace App\Http\Controllers\GeneralLedgers;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\GeneralLedgers\GeneralLedger;
use App\Models\GeneralLedgers\OpeningTransaction;
use App\Models\LedgerCalendars\LedgerCalendar;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;
use Carbon\Carbon;

class GeneralLedgerFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new GeneralLedger;
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
                'id' => $item->id,
                'name' => $item->name,
                'ledger_journal_code' => $item->ledger_journal_code,
                'period_from' => Carbon::parse($item->period_from)->format('m/d/Y'),
                'period_to' => Carbon::parse($item->period_to)->format('m/d/Y'),
                'total_debit' => $item->general_ledger_lines->sum('credit_amount'),
                'total_credit' => $item->general_ledger_lines->sum('debit_amount'),
                'total_journal_lines' => $item->general_ledger_lines->count(),
                'reverse_date' => $item->reverse_date ? $item->reverse_date : '-',
                'adjusting_date' => $item->adjusting_date ? $item->adjusting_date : '-',
                'posted_on' => $item->posted_on ? $item->posted_on : '-',
                'created_at' => $item->renderDate(),
                'deleted_at' => $item->deleted_at,
            ]);

            array_push($result, $data);
        }

        return $result;
    }

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
        $main_accounts = MainAccount::where('company_id', auth()->user()->company_id)->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->with('chart_of_account')->get();
        $ledger_calendars = LedgerCalendar::where('company_id', auth()->user()->company_id)->get();
        $opening_transaction_journals = OpeningTransaction::where('company_id', auth()->user()->company_id)->get();

        if ($id) {
            $item = GeneralLedger::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'main_accounts' => $main_accounts,
            'ledgers' => $ledgers,
            'ledger_calendars'=> $ledger_calendars,
            'opening_transaction_journals' => $opening_transaction_journals,
        ]);
    }

    protected function formatView($item)
    {
        $item->chart_of_account = $item->chart_of_account ? $item->chart_of_account->coa_name : '---';
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->approveClosingBalanceUrl = $item->renderApproveClosingBalanceUrl();
        $item->total_debit = $item->getDebit();
        $item->total_credit = $item->getCredit();
        $item->should_validate_closing_transaction = $item->shouldValidateClosingTransacion();
        $item->closingAuthenticationUrl = $item->renderClosingAuthentication();

        $item->archive_payables_url = $item->renderArchiveAccountsPayableUrl();
        $item->archive_receivables_url = $item->renderArchiveAccountsReceivableUrl();

        $item->total_assets = $item->getAssets();
        $item->total_liabilities = $item->getLiabilities();
        $item->total_income = $item->getIncome();
        $item->total_profit_and_loss = $item->getProfitAndLoss();
        $item->total_expense = $item->getProfitAndLoss();
        $item->total_equities = $item->getEquities();

        if($item->closing_transaction) {
            $item->closing_transaction_fetch_url = $item->closing_transaction->renderFetchUrl();
            $item->closing_transaction_update_url = $item->closing_transaction->renderUpdateUrl();
        }

        return $item;
    }
}