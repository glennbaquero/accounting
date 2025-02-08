<?php

namespace App\Models\GeneralLedgers;

use App\Extenders\Models\BaseModel;
use App\Models\AdminSetups\Client;
use App\Models\AdminSetups\Company;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

use Auth;
use Carbon\Carbon;

class GeneralLedger extends BaseModel
{
    const CLOSING_OPEN = 'Open';
    const CLOSING_ON_HOLD = 'On Hold';
    const CLOSING_CLOSED = 'Closed';

    protected $appends = [
        'posted_by_fullname', 
        'adjusted_by_fullname', 
        'reverse_by_fullname', 
        'created_by_fullname', 
        'updated_by_fullname'
    ];

    public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'name' => $this->name,
	    ];
	}

    /**
     *  Relationships
     */

    public function posted_by_user() {
        
        return $this->belongsTo(User::class, 'posted_by');

    }

    public function adjusted_by_fullname() {

        return $this->belongsTo(User::class, 'adjusted_by');

    }

    public function reverse_by_user() {

        return $this->belongsTo(User::class, 'reverse_by');

    }

    public function created_by_user() {

        return $this->belongsTo(User::class, 'created_by');

    }

    public function updated_by_user() {

        return $this->belongsTo(User::class, 'updated_by');

    }

    public function main_account() {

        return $this->belongsTo(MainAccount::class, 'updated_by');

    }

    public function company() {

        return $this->belongsTo(Company::class, 'updated_by');

    }

    public function ledger() {

        return $this->belongsTo(Ledger::class, 'ledger_id')->withTrashed();
    }


    public function general_ledger_lines() {

        return $this->hasMany(GeneralLedgerLine::class);

    }

    public function client() {

        return $this->belongsTo(Client::class);
        
    }

    public function closing_transaction() {

        return $this->hasOne(ClosingTransaction::class, 'id', 'closing_transaction_id');

    }

    public function opening_transaction() {

        return $this->belongsTo(OpeningTransaction::class, 'opening_transaction_journal_id');

    }

    /**
     *  Attributes
     */

    public function getPostedByFullnameAttribute() {

        return $this->posted_by_user ? $this->posted_by_user->renderName() : '-';

    }

    public function getAdjustedByFullnameAttribute() {

        return $this->adjusted_by_user ? $this->adjusted_by_user->renderName() : '-';

    }

    public function getReverseByFullnameAttribute() {

        return $this->reverse_by_user ? $this->reverse_by_user->renderName() : '-';
        
    }

    public function getUpdatedByFullnameAttribute() {

        return $this->updated_by_user ? $this->updated_by_user->renderName() : '-';

    }

    public function getCreatedByFullnameAttribute() {

        return $this->created_by_user ? $this->created_by_user->renderName() : '-';

    }

    /**
     *  Setters
     */
    public static function store($request, $item = null, $columns = [
        'name',
        'ledger_journal_code',
        'client_id',
        'period_from',
        'period_to',
        'main_account_id', 
        'ledger_id', 
        'ledger_calendar_id', 
        'description', 
        'opening_transaction_journal_id'])
	{
		$vars = $request->only($columns);

        $vars['ledger_journal_status'] = '-';
        $vars['use_retain_earnings_as_opening'] = $request->filled('use_retain_earnings_as_opening') ? true : false;
        $vars['company_id'] = auth()->user()->company_id;
        
	    if (!$item) {

            $vars['created_by'] = Auth::user()->id;
	        $item = static::create($vars);

	    } else {

            $vars['updated_by'] = Auth::user()->id;
            $vars['updated_at'] = now();
	        $item->update($vars);
            
	    }

	    return $item;
	}

    /**
     *  Renders
     */
    public function renderShowUrl() {

        return route('general-ledgers.show', $this->id);

    }

    public function renderArchiveUrl() {

        return route('general-ledgers.archive', $this->id);
    }


    public function renderRestoreUrl() {

        return route('general-ledgers.restore', $this->id);

    }

    public function renderApproveClosingBalanceUrl() {

        return route('general-ledgers.approve-closing-balance', $this->id);

    }
    public function renderClosingAuthentication() {

        return route('general-ledgers.closing-authentication', $this->id);

    }
    
    public function renderArchiveAccountsReceivableUrl() {

        return route('general-ledgers.archive-accounts-recievable', $this->id);

    }

    public function renderArchiveAccountsPayableUrl() {

        return route('general-ledgers.archive-accounts-payable', $this->id);

    }

    public function renderArchiveInventoriesUrl() {

        return route('general-ledgers.archive-inventories', $this->id);

    }

    public function renderArchiveCashAndBankUrl() {

        return route('general-ledgers.archive-cash-and-bank', $this->id);

    }

    public function renderArchiveGeneralLedgerUrl() {

        return route('general-ledgers.archive-general-ledger', $this->id);

    }


    /**
     *  Getters
     */

    public function getPeriods() {

        return $this->ledger->ledger_calendar->fiscal_calendar->fiscal_periods;

    }

    public function getClosingPeriod() {

        return $this->getPeriods()->where('fiscal_period_type', 'Closing')->first();

    }
    

    public function getClosingPeriodRange() {

        $last_day = $this->getClosingPeriod()->fiscal_period_end_date;
        $first_day = Carbon::parse($last_day)->subDays(7)->format('Y-m-d');
        
        return ['first_day' => $first_day, 'last_day' => $last_day];

    }

    public function getClosingPeriodRangeFirstDay() {

        return $this->getClosingPeriodRange()['first_day'];

    }

    public function getClosingPeriodRangeLastDay() {
        
        return $this->getClosingPeriodRange()['last_day'];

    }

    public static function getClosingStatuses() {
        return [
            [ 'value' => static::CLOSING_OPEN , 'name' => 'Open'],
            [ 'value' => static::CLOSING_ON_HOLD, 'name' => 'On Hold'],
            [ 'value' => static::CLOSING_CLOSED , 'name' =>'Closed'],
        ];
    }


    public function getOpeningPeriod() {

        return $this->getPeriods()->where('fiscal_period_type', 'Opening')->first();

    }

    public function getOpeningBalance() {

        $opening_transaction = $this->opening_transaction;
        
        if($opening_transaction) {

            $general_ledger = $opening_transaction->general_ledger;

            if($general_ledger->use_retain_earnings_as_opening) {

                return $this->getRetainedEarnings();

            }else {

                return $this->getAssets() + $this->getLiabilities() + $this->getEquities();

            }
        }

        return 0.00;

    }

    public function getDebit() {

        return $this->general_ledger_lines->sum('debit_amount');

    }

    public function getCredit() {

        return $this->general_ledger_lines->sum('credit_amount');

    }

    public function getProfitAndLoss() {

        $profit_and_loss = $this->general_ledger_lines()
        ->whereHas('main_account_relation', function($query) {
            $query->where('main_account_type', 'Profit and loss');
        });

        $credit = $profit_and_loss->sum('credit_amount');
        $debit = $profit_and_loss->sum('debit_amount');

        return $credit - $debit;
    
    }

    public function getAssets() {

        $profit_and_loss = $this->general_ledger_lines()
        ->whereHas('main_account_relation', function($query) {
            $query->where('main_account_type', 'Asset');
        });

        $credit = $profit_and_loss->sum('credit_amount');
        $debit = $profit_and_loss->sum('debit_amount');

        return $credit - $debit;
    
    }

    public function getLiabilities() {

        $profit_and_loss = $this->general_ledger_lines()
        ->whereHas('main_account_relation', function($query) {
            $query->where('main_account_type', 'Liability');
        });

        $credit = $profit_and_loss->sum('credit_amount');
        $debit = $profit_and_loss->sum('debit_amount');

        return $credit - $debit;
    
    }

    public function getIncome() {

        $profit_and_loss = $this->general_ledger_lines()
        ->whereHas('main_account_relation', function($query) {
            $query->where('main_account_type', 'Income');
        });

        $credit = $profit_and_loss->sum('credit_amount');
        $debit = $profit_and_loss->sum('debit_amount');

        return $credit - $debit;
    
    }

    
    public function getEquities() {

        $profit_and_loss = $this->general_ledger_lines()
        ->whereHas('main_account_relation', function($query) {
            $query->where('main_account_type', 'Equity');
        });

        $credit = $profit_and_loss->sum('credit_amount');
        $debit = $profit_and_loss->sum('debit_amount');

        return $credit - $debit;
    
    }

    public function getExpense() {

        $profit_and_loss = $this->general_ledger_lines()
        ->whereHas('main_account_relation', function($query) {
            $query->where('main_account_type', 'Expense');
        });

        $credit = $profit_and_loss->sum('credit_amount');
        $debit = $profit_and_loss->sum('debit_amount');

        return $credit - $debit;
    
    }

    public function getRevenue() {

        $profit_and_loss = $this->general_ledger_lines()
        ->whereHas('main_account_relation', function($query) {
            $query->where('main_account_type', 'Revenue');
        });

        $credit = $profit_and_loss->sum('credit_amount');
        $debit = $profit_and_loss->sum('debit_amount');

        return $credit - $debit;
    
    }
    
    public function getIncomeSummary() {

        return $this->getRevenue() - $this->getExpense();

    }

    public function getExpenseSummary() {

        return $this->getExpense();

    }

    public function getIncomeSummaryAccountFromIncome() {

        return $this->getIncomeSummary() - $this->getExpenseSummary();

    }

    public function getIncomeSummaryAccountFromExpense() {

        return $this->getExpenseSummary();

    }

    public function getRetainedEarnings() {

        $income_summary = $this->getIncomeSummary();
        
        return $income_summary <= 0 ? $income_summary : - $income_summary;

    }

    public function getDividends() {

        $retain_earnings = $this->getRetainedEarnings();
        
        return $retain_earnings <= 0 ? $retain_earnings : - $retain_earnings;

    }

    /**
     *  Checkers
     */

    public function checkIfApproveClosingBalance() {

        if($this->approve_closing_balance_date && $this->approve_closing_balance_date_by) {
            return true;
        }

        return false;
        
    }

    public function checkIfEnabledClosingTransaction() {

        if($this->enabled_closing_date && $this->enabled_closing_by) {
            return true;
        }

        return false;
        
    }

    public function shouldValidateClosingTransacion() {

        if ($this->closing_transaction) {
            
            if($this->closing_transaction->hasPassword()) {

                return true;

            }
    
            return false;
        }

        return false;
    }
    
}
