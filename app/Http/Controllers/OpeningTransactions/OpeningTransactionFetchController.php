<?php

namespace App\Http\Controllers\OpeningTransactions;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\GeneralLedgers\GeneralLedger;
use App\Models\GeneralLedgers\OpeningTransaction;
use App\Models\LedgerCalendars\LedgerCalendar;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;
use Carbon\Carbon;

class OpeningTransactionFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new OpeningTransaction;
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
        $main_accounts = MainAccount::where('company_id', auth()->user()->company_id)->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->with('chart_of_account','general_ledger','ledger_calendar')->get();
        $ledger_calendars = LedgerCalendar::where('company_id', auth()->user()->company_id)->get();
        $general_ledgers = GeneralLedger::where('company_id', auth()->user()->company_id)->get();

        if ($id) {
            $item = OpeningTransaction::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'main_accounts' => $main_accounts,
            'ledgers' => $ledgers,
            'ledger_calendars' => $ledger_calendars,
            'general_ledgers' => $general_ledgers,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        
        return $item;
    }
}