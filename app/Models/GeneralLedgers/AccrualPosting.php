<?php

namespace App\Models\GeneralLedgers;

use App\Extenders\Models\BaseModel;
use App\Models\AdminSetups\Client;
use App\Models\JournalLines\GeneralJournalVoucher;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

class AccrualPosting extends BaseModel
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	    ];
	}

	protected $appends = ['approved_by_fullname', 'created_by_fullname', 'updated_by_fullname', 'rejected_by_fullname'];

	/**
	* Relationships
	*/

    public function debit_account() {
        return $this->belongsTo(MainAccount::class, 'ledger_posting_debit_account_number');
    }

	public function ledger() {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }

    public function credit_account() {
        return $this->belongsTo(MainAccount::class, 'ledger_posting_credit_account_number');
    }
    
    public function client() {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function main_account() {
        return $this->belongsTo(MainAccount::class, 'main_account_id');
    }

	public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by');
    }

	public function approved_by_user() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejected_by_user() {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function general_journal_vouchers() {
        return $this->hasMany(GeneralJournalVoucher::class, 'accrual_id');
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, 
    $columns = ['accrual_id', 'accrual_posting', 'accrual_status', 'prepared_by', 'period_start', 'period_end', 'fiscal_period_start_date',
    'ledger_posting_debit_account_number', 'ledger_posting_debit', 'ledger_posting_credit', 'ledger_id', 'fiscal_period_end_date',
    'description', 'calendar_type', 'period_frequency', 'length', 'posting_date', 'approved_invoice_checkbox', 
    'approved_date', 'approved_by', 'client_id', 'ledger_posting_credit_account_number', 'main_account_id'])
	{   
        $auth = auth()->user();
	    $vars = $request->only($columns);
		$vars['company_id'] = $auth->company_id;
	
	    if (!$item) {
            $vars['created_by'] = $auth->id;
	        $item = static::create($vars);
	    } else {
            $vars['updated_by'] = $auth->id;
            $vars['updated_on'] = now();
	        $item->update($vars);
	    }

	    return $item;
	}


	/**
     *  Attributes
     */

	public function getApprovedByFullnameAttribute() {
        return $this->approved_by_user ? $this->approved_by_user->renderName() : '-';
    }

    public function getRejectedByFullnameAttribute() {
        return $this->rejected_by_user ? $this->rejected_by_user->renderName() : '-';
    }

    public function getUpdatedByFullnameAttribute() {
        return $this->updated_by_user ? $this->updated_by_user->renderName() : '-';
    }

    public function getCreatedByFullnameAttribute() {
        return $this->created_by_user ? $this->created_by_user->renderName() : '-';
    }

	/**
	 * Renderers
	 */
	public function renderShowUrl() {
        return route('accrual-postings.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('accrual-postings.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('accrual-postings.restore', $this->id);
    }

	public function renderApproveUrl() {
        return route('accrual-postings.status-update', ['id' => $this->id, 'status' => 1]);
    }

    public function renderRejectUrl() {
        return route('accrual-postings.status-update', ['id' => $this->id, 'status' => 0]);
    }

    public function renderStatus() {
        if(!$this->approved_date && !$this->rejected_on) {
            return ['label' => 'pending', 'class' => 'badge-warning'];
        }
        if(!$this->approved_date && $this->rejected_on) {
            return ['label' => 'rejected', 'class' => 'badge-danger'];
        }
        if($this->approved_date && !$this->rejected_on) {
            return ['label' => 'approved', 'class' => 'badge-success'];
        }
    }
}
