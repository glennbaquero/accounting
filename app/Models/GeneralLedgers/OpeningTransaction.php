<?php

namespace App\Models\GeneralLedgers;

use App\Extenders\Models\BaseModel;
use App\Models\AdminSetups\Client;
use App\Models\AdminSetups\Company;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

use Auth;

class OpeningTransaction extends BaseModel
{
    protected $appends = ['posted_by_fullname', 'adjusted_by_fullname', 'reverse_by_fullname', 'created_by_fullname', 'updated_by_fullname', 'general_ledger_name'];

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
        return $this->belongsTo(MainAccount::class, 'main_account_id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function ledger() {
        return $this->belongsTo(Ledger::class, 'ledger_id')->withTrashed();
    }

    public function ledger_journal() {
        return $this->belongsTo(GeneralLedger::class, 'ledger_journal_id')->withTrashed();
    }

    public function general_ledger() {
        return $this->belongsTo(GeneralLedger::class, 'general_ledger_id')->withTrashed();
    }
    

    public function client() {
        return $this->belongsTo(Client::class, 'client_id');
    }


    /**
     *  Attributes
     */

    public function getPostedByFullnameAttribute() {
        return $this->posted_by_user ? $this->posted_by_user->renderName() : '---';
    }

    public function getAdjustedByFullnameAttribute() {
        return $this->adjusted_by_user ? $this->adjusted_by_user->renderName() : '---';
    }

    public function getReverseByFullnameAttribute() {
        return $this->reverse_by_user ? $this->reverse_by_user->renderName() : '---';
    }

    public function getUpdatedByFullnameAttribute() {
        return $this->updated_by_user ? $this->updated_by_user->renderName() : '---';
    }

    public function getCreatedByFullnameAttribute() {
        return $this->created_by_user ? $this->created_by_user->renderName() : '---';
    }

    public function getGeneralLedgerNameAttribute() {
        return $this->general_ledger ? $this->general_ledger->name : '---';
    }


    /**
     *  Setters
     */
    public static function store($request, $item = null, 
    $columns = [ "general_ledger_id", "ledger_journal_id", "company_id",
    "client_id", "main_account_id", "main_account_normal_balance", 'ledger_calendar_id',
    "period_from", "period_to", "ledger_journal_status", "debit",
    "credit", "balance", "reversed_checkbox", "reverse_date", "ledger_id",
    "reverse_by", "adjusted_checkbox", "adjusted_on", "adjusted_by", 
    "posted_checkbox", "posted_on", "posted_by", "description",
    "updated_on", "updated_by", "created_at", "updated_at"])
	{
		$vars = $request->only($columns);

        $vars['company_id'] = auth()->user()->id;
        
	    if (!$item) {
            $vars['created_by'] = Auth::user()->id;
	        $item = static::create($vars);
	    } else {
            $vars['updated_by'] = Auth::user()->id;
	        $item->update($vars);
	    }

	    return $item;
	}

    /**
     *  Renders
     */
    public function renderShowUrl() {
        return route('opening-transactions.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('opening-transactions.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('opening-transactions.restore', $this->id);
    }


    
}
