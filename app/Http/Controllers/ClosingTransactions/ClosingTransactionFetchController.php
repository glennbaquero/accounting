<?php

namespace App\Http\Controllers\ClosingTransactions;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\GeneralLedgers\ClosingTransaction;
use App\Models\GeneralLedgers\GeneralLedger;
use App\Models\LedgerCalendars\LedgerCalendar;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;
use Carbon\Carbon;

class ClosingTransactionFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new ClosingTransaction;
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
                'name' => $item->name,
                'client' => $item->client ? $item->client->name : '---',
                'main_account_id' => $item->main_account->main_account_id,    
                'main_account_code' => $item->main_account->main_account_code,                            
                'main_account_name' => $item->main_account->main_account_name,
                'main_account_type' => $item->main_account->main_account_type,
                'general_ledger' => $item->general_ledger->name,
                'main_account_category_id' => $item->main_account->main_account_category_selected->main_account_category,
                'normal_balance' => $item->normal_balance ?? '---',
                'opening_balance_status' => $item->ledger_journal_status ?? '---',
                'balance' => $item->balance,
                'debit' => $item->debit ?? '---',
                'credit' => $item->credit ?? '---',
                'ledger_journal_code' => $item->ledger_journal_code,
                'period_from' => Carbon::parse($item->period_from)->format('m/d/Y'),
                'period_to' => Carbon::parse($item->period_to)->format('m/d/Y'),
                'reverse_date' => $item->reverse_date ? $item->reverse_date : '---',
                'adjusting_date' => $item->adjusting_date ? $item->adjusting_date : '---',
                'posted_on' => $item->posted_on ? $item->posted_on : '---',
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
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->with('chart_of_account','general_ledger','ledger_calendar')->get();
        $general_ledgers = GeneralLedger::where('company_id', auth()->user()->company_id)->get();
        $closing_statuses = ClosingTransaction::getClosingStatuses();

        if ($id) {
            $item = ClosingTransaction::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'ledgers' => $ledgers,
            'general_ledgers' => $general_ledgers,
            'closing_statuses' => $closing_statuses,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->approvedUrl = $item->renderApproveUrl();
        $item->reviewedUrl = $item->renderReviewUrl();
        $item->reviewedUrl = $item->renderReviewUrl();
        $item->canSetPassword = $item->renderCanSetPasswordUrl();

        $item->archive_accounts_payable_by_fullname = $item->archive_accounts_payable_by_user ? $item->archive_accounts_payable_by_user->renderName() : '---';
        $item->archive_accounts_receivable_by_fullname = $item->archive_accounts_payable_by_user ? $item->archive_accounts_receivable_by_user->renderName() : '---';
        $item->archive_accounts_inventories_by_fullname = $item->archive_inventories_by_user ? $item->archive_inventories_by_user->renderName() : '---';
        $item->archive_cash_and_bank_by_fullname = $item->archive_cash_and_bank_by_user ? $item->archive_cash_and_bank_by_user->renderName() : '---';
        $item->archive_general_ledgers_by_fullname =  $item->archive_general_ledgers_by_user ? $item->archive_general_ledgers_by_user->renderName() : '---';

        if($item->general_ledger) {
            $item->archive_payables_url = $item->general_ledger->renderArchiveAccountsPayableUrl();
            $item->archive_receivables_url = $item->general_ledger->renderArchiveAccountsReceivableUrl();
            $item->archive_inventories_url = $item->general_ledger->renderArchiveInventoriesUrl();
            $item->archive_cash_and_bank_url = $item->general_ledger->renderArchiveCashAndBankUrl();
            $item->archive_general_ledger_url = $item->general_ledger->renderArchiveGeneralLedgerUrl();

            $item->income_summary_account_from_income_amount = $item->general_ledger->getIncomeSummaryAccountFromIncome();
            $item->income_summary_account_from_expense_amount = $item->general_ledger->getIncomeSummaryAccountFromExpense();
            $item->income_summary_amount = $item->general_ledger->getIncomeSummaryAccountFromExpense();
            $item->retained_earnings_amount = $item->general_ledger->getRetainedEarnings();
            $item->dividends_amount = $item->general_ledger->getDividends();

            $item->income_summary_account_from_income = 'Income Amount Account';
            $item->income_summary_account_from_expense = 'Expense Amount Account';
            $item->income_summary_account = 'Income Summary';
            $item->retained_earnings_account = 'Retained Earnings';
            $item->dividends_account = 'Dividends';
        }

        if($item->general_ledger->opening_transaction) {

            $item->opening_transaction = $item->general_ledger->opening_transaction->general_ledger->name;
            
        }else {
            $item->opening_transaction = null;
        }
                
        return $item;
    }
}