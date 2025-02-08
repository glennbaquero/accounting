<?php

namespace App\Models\GeneralLedgers;

use App\Extenders\Models\BaseModel;
use App\Models\AdminSetups\Client;
use App\Models\AdminSetups\Company;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

use Auth;

class ClosingTransaction extends BaseModel
{

    const CLOSING_OPEN = 'Open';
    const CLOSING_ON_HOLD = 'On Hold';
    const CLOSING_CLOSED = 'Closed';

    protected $hidden = ['password'];

    protected $appends = [
        'posted_by_fullname', 
        'adjusted_by_fullname', 
        'reverse_by_fullname', 
        'created_by_fullname', 
        'updated_by_fullname', 
        'general_ledger_name', 
        'updated_by_fullname',
        'reviewed_by_fullname',
        'approved_by_fullname',
        'password_set_by_fullname',
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

    public function approved_by_user() {
        
        return $this->belongsTo(User::class, 'approved_by');
        
    }

    public function reviewed_by_user() {

        return $this->belongsTo(User::class, 'reviewed_by');

    }

    public function adjusted_by_fullname() {

        return $this->belongsTo(User::class, 'adjusted_by');

    }

    public function reverse_by_user() {

        return $this->belongsTo(User::class, 'reverse_by');

    }

    public function archive_accounts_payable_by_user() {

        return $this->belongsTo(User::class, 'archive_payables_by');

    }

    public function archive_accounts_receivable_by_user() {

        return $this->belongsTo(User::class, 'archive_receivable_by');

    }

    public function archive_inventories_by_user() {

        return $this->belongsTo(User::class, 'archive_inventories_by');

    }

    public function archive_cash_and_bank_by_user() {

        return $this->belongsTo(User::class, 'archive_cash_and_bank_by');

    }

    public function archive_general_ledgers_by_user() {

        return $this->belongsTo(User::class, 'archive_general_ledger_by');

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

    public function password_set_by_user() {

        return $this->belongsTo(User::class, 'password_set_by');

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

    public function getApprovedByFullnameAttribute() {

        return $this->approved_by_user ? $this->approved_by_user->renderName() : '---';

    }

    public function getReviewedByFullnameAttribute() {

        return $this->reviewed_by_user ? $this->reviewed_by_user->renderName() : '---';

    }

    public function getPasswordSetByFullnameAttribute() {

        return $this->password_set_by_user ? $this->password_set_by_user->renderName() : '---';

    }


    /**
     *  Setters
     */
    public static function store($request, $item = null, 
    $columns = [ 
        "general_ledger_id", 
        "ledger_id", 
        "opening_transaction_id", 
        "closing_date", 
        "client_id",
        "closing_status",
        "closing_period_start",
        "closing_period_end",
    ])
	{
		$vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->id;
        $user = auth()->user()->id;
        
	    if (!$item) {

            $vars['created_by'] = $user;
	        $item = static::create($vars);

	    } else {

            $vars['updated_by'] = $user;
            $vars['updated_on'] = now();
	        $item->update($vars);

	    }

	    return $item;
	}

    /**
     *  Renders
     */
    public function renderShowUrl() {
        
        return route('closing-transactions.show', $this->id);

    }

    public function renderArchiveUrl() {

        return route('closing-transactions.archive', $this->id);

    }

    public function renderRestoreUrl() {

        return route('closing-transactions.restore', $this->id);

    }

    public function renderFetchUrl() {

        return route('closing-transactions.fetch-item', $this->id);

    }

    public function renderUpdateUrl() {

        return route('closing-transactions.update', $this->id);

    }

    public function renderApproveUrl() {

        return route('closing-transactions.approved', $this->id);

    }

    public function renderReviewUrl() {

        return route('closing-transactions.reviewed', $this->id);

    }

    public function renderCanSetPasswordUrl() {

        return route('closing-transactions.can-set-password', $this->id);

    }
    

    /**
     *  Getters
     */

    public static function getClosingStatuses() {

        return [
            [ 'value' => static::CLOSING_OPEN , 'name' => 'Open'],
            [ 'value' => static::CLOSING_ON_HOLD, 'name' => 'On Hold'],
            [ 'value' => static::CLOSING_CLOSED , 'name' =>'Closed'],
        ];

    }

    /**
     *  Checkers
     */

    public function checkIfApproved() {

        if($this->approved_by && $this->approved_on) {
            return true;
        }
        
        return false;
    }

    public function checkIfReviewed() {

        if($this->reviewed_by && $this->reviewed_on) {
            return true;
        }

        return false;
    }

    public function canApproved() {

        if($this->checkIfReviewed() && !$this->checkIfApproved()) {
            return true;
        }

        return false;
    }

    public function canReviewed() {

        if(!$this->checkIfReviewed() && !$this->checkIfApproved()) {
            return true;
        }

        return false;
    }

    public function hasPassword() {

        if($this->password && $this->password_set_by && $this->password_set_on) {
            return true;
        }

        return false;
    }

}
