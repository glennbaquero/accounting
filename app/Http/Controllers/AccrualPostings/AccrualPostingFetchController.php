<?php

namespace App\Http\Controllers\AccrualPostings;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\GeneralLedgers\AccrualPosting;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

class AccrualPostingFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new AccrualPosting;
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

        if($this->request->filled('status')) {
            $status = $this->request->status;
            if($status == 'approved') {
                $query = $query->whereNotNull('approved_date');
            }
            if($status == 'rejected') {
                $query = $query->whereNotNull('rejected_on');
            }
            if($status == 'pending') {
                $query = $query->whereNull('rejected_on')->whereNull('approved_date');
            }
        }

        if($this->request->filled('client_id')) {
            $query = $query->where('client_id', $this->request->client)->where('general_journal_number', $this->request->id);
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
                'client' => $item->client->name ?? '---',
                'ledger' => $item->ledger->ledger_name ?? '---',
                'status' => $item->renderStatus(),
                'main_account' => $item->main_account->main_account_name ?? '---',
                'accrual_posting' => $item->accrual_posting,
                'debit_account_number' => $item->debit_account->main_account_code,
                'ledger_posting_debit' => $item->ledger_posting_debit,
                'credit_account_number' => $item->credit_account->main_account_code,
                'ledger_posting_credit' => $item->ledger_posting_credit,
                'calendar_type' => $item->calendar_type,
                'length' => $item->length,
                'posting_date' => $item->posting_date,
                'approved_date' => $item->approved_date ?? '---',
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
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->with('ledger_calendar.fiscal_calendar')->get();

        if ($id) {
            $item = AccrualPosting::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'ledgers' => $ledgers,
            'main_accounts' => $main_accounts,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->approveUrl = $item->renderApproveUrl();
        $item->rejectUrl = $item->renderRejectUrl();

        return $item;
    }
}