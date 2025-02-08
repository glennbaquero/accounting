<?php

namespace App\Models\BankAccountStatements;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\AdminSetups\Client;

use App\Models\BankAccountTransactions\BankAccountTransaction;

use Carbon\Carbon;

class BankAccountStatement extends Model
{
	protected $appends = [
		'client_bank_account_holder',
		'client_bank_account_type',
		'client_bank_name',
		'client_bank_branch',
		'client_bank_account_expiry',
	];

	/**
	 * @Relationship
	 */
	public function statement_lines() {
		return $this->hasMany(BankAccountStatementLine::class, 'bank_statement_id', 'statement_id');
	}

	public function transaction() {
		return $this->belongsTo(BankAccountTransaction::class, 'bank_account_transaction_number', 'bank_account_transaction_number');
	}

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'bank_statement' => $this->bank_statement,
			'bank_statement_id' => $this->bank_statement_id,
			'client_bank_account_number' => $this->client_bank_account_number,
			'bank_account_transaction_number' => $this->bank_account_transaction_number,
			'bank_statement_issue_date' => $this->bank_statement_issue_date,
			'bank_statement_from_date' => $this->bank_statement_from_date,
			'bank_statement_to_date' => $this->bank_statement_to_date,
			'prepared_by' => $this->prepared_by,
			'cost_center' => $this->cost_center,
			'department' => $this->department,
			'currency' => $this->currency,
			'opening_balance' => $this->opening_balance,
			'ending_balance' => $this->ending_balance,
	    ];
	}

	/**
	 * Relationships
	 */
	public function client() {
		return $this->belongsTo(Client::class)->withTrashed();
	}

	public function client_bank_account() {
		return $this->belongsTo(ClientBankAccount::class, 'client_bank_account_number', 'bank_account')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	public function approved_by_user() {
		return $this->belongsTo(User::class, 'approved_by', 'id')->withTrashed();
	}

	public function canceled_by_user() {
		return $this->belongsTo(User::class, 'canceled_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'bank_statement',
		// 'bank_statement_id',
		'client_bank_account_number',
		'bank_account_transaction_number',
		'bank_statement_issue_date',
		'bank_statement_from_date',
		'bank_statement_to_date',
		'prepared_by',
		'cost_center',
		'department',
		'currency',
		'opening_balance',
		'ending_balance',
	])
	{

		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $bank_statement_id = 'BA-statement-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'bank_statement_id' => $bank_statement_id,
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        ]);
	    } else {
	        $item->update($vars);
	        $item->update([
	        	'updated_by' => $request->user()->id,
	        ]);
	    }

	    return $item;
	}

	public function markCanceled($request) {
		if($this->approved_date) {
			return false;
		}

		return $this->update([
			'canceled' => true, 
			'canceled_date' => now(), 
			'canceled_by' => $request->user()->id,
		]);
	}

	public function markApproved($request) {
		if($this->canceled_date) {
			return false;
		}

		return $this->update([
			'approved' => true, 
			'approved_date' => now(),
			'approved_by' => $request->user()->id,
		]);
	}

	// Client Bank

	public function getClientBankAccountHolderAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->account_holder : '---';
	}

	public function getClientBankAccountTypeAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->bank_account_type : '---';
	}

	public function getClientBankNameAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->bank_name : '---';
	}

	public function getClientBankBranchAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->bank_branch : '---';
	}

	public function getClientBankAccountExpiryAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->expiration_date : '---';
	}


	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderApprovedUser() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}

	public function renderCanceledUser() {
		return $this->canceled_by_user ? $this->canceled_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('bank-account-statements.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-account-statements.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-account-statements.restore', $this->id);
    }

    public function renderApproveUrl() {
        return route('bank-account-statements.approve', $this->id);
    }

    public function renderCancelUrl() {
        return route('bank-account-statements.cancel', $this->id);
    }

}
