<?php

namespace App\Models\BankReconciliations;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\CashflowTransactions\CashflowTransaction;
use App\Models\BankAccountStatements\BankAccountStatement;

class BankReconciliation extends Model
{
	protected $casts = [
		'bank_statement_id' => 'integer',
		'cash_register_id' => 'integer',
	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'client_id' => $this->client_id,
			'company_id' => $this->company_id,
			'bank_reconciliation_id' => $this->bank_reconciliation_id,
			'name' => $this->name,
			'description' => $this->description,
			'reconciled_date' => $this->reconciled_date,
			'reconciled_by' => $this->reconciled_by,
			'reconciled_checkbox' => $this->reconciled_checkbox,
			'posted_date' => $this->posted_date,
			'posted_by' => $this->posted_by,
			'posted_checkbox' => $this->posted_checkbox,
			'approved_date' => $this->approved_date,
			'approved_by' => $this->approved_by,
			'approved_checkbox' => $this->approved_checkbox,
			'ending_balance' => $this->ending_balance,
			'reconciled_transactions' => $this->reconciled_transactions,
			'unreconciled_transactions' => $this->unreconciled_transactions,
			'client_bank_account' => $this->client_bank_account,
			'bank_account_number' => $this->bank_account_number,
			'bank_account_type' => $this->bank_account_type,
			'bank_statement_id' => $this->bank_statement_id,
			'statement_as_of_date' => $this->statement_as_of_date,
			'statement_ending_balance' => $this->statement_ending_balance,
			'statement_total_amount' => $this->statement_total_amount,
			'statement_open_amount' => $this->statement_open_amount,
			'balance_per_bank_statement' => $this->balance_per_bank_statement,
			'cash_register_id' => $this->cash_register_id,
			'cash_register_as_of_date' => $this->cash_register_as_of_date,
			'cash_register_ending_balance' => $this->cash_register_ending_balance,
			'cash_register_total_amount' => $this->cash_register_total_amount,
			'cash_register_open_amount' => $this->cash_register_open_amount,
			'balance_per_cash_register' => $this->balance_per_cash_register,
			'cash_register_description' => $this->cash_register_description,
	    ];
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'name',
		'client_id',
		'description',
		// 'reconciled_date',
		// 'reconciled_by',
		// 'reconciled_checkbox',

		// 'posted_date',
		// 'posted_by',
		// 'posted_checkbox',

		// 'approved_date',
		// 'approved_by',
		// 'approved_checkbox',
		'ending_balance',
		'reconciled_transactions',
		'unreconciled_transactions',
		'client_bank_account',
		
		'bank_account_number',
		'bank_account_type',
		'bank_statement_id',

		'statement_as_of_date',
		'statement_ending_balance',
		'statement_total_amount',
		'statement_open_amount',
		'balance_per_bank_statement',

		'cash_register_id',
		'cash_register_as_of_date',
		'cash_register_ending_balance',
		'cash_register_total_amount',
		'cash_register_open_amount',

		'balance_per_cash_register',
		'cash_register_description',
	])
	{

		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $bank_reconciliation_id = 'BA-reconciliation-id-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'bank_reconciliation_id' => $bank_reconciliation_id,
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

	public function markReconciled($request) {

		return $this->update([
			'reconciled_checkbox' => true,
			'reconciled_date' => now(),
			'reconciled_by' => $request->user()->id,
		]);
	}

	public function markApproved($request) {

		return $this->update([
			'approved_checkbox' => true,
			'approved_date' => now(),
			'approved_by' => $request->user()->id,
		]);
	}

	public function markPosted($request) {

		return $this->update([
			'posted_checkbox' => true,
			'posted_date' => now(),
			'posted_by' => $request->user()->id,
		]);
	}

	/**
	 * @Relationships
	 */
	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	public function approved_by_user() {
		return $this->belongsTo(User::class, 'approved_by', 'id')->withTrashed();
	}

	public function reconciled_by_user() {
		return $this->belongsTo(User::class, 'reconciled_by', 'id')->withTrashed();
	}

	public function posted_by_user() {
		return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
	}

	public function cash_register() {
		return $this->belongsTo(CashflowTransaction::class, 'cash_register_id', 'id')->withTrashed();
	}

	public function bank_statement() {
		return $this->belongsTo(BankAccountStatement::class, 'bank_statement_id', 'id')->withTrashed();
	}

	public function client_bank_account() {
		return $this->belongsTo(ClientBankAccount::class, 'client_bank_account', 'bank_account')->withTrashed();
	}

	public function bank_reconciliation_lines() {
		return $this->hasMany(BankReconciliationLine::class, 'bank_reconciliation_id', 'bank_reconciliation_id');
	}

	/**
	 * @Renders
	 */
	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderReconciledBy() {
		return $this->reconciled_by_user ? $this->reconciled_by_user->renderName() : ''; 
	}

	public function renderPostedBy() {
		return $this->posted_by_user ? $this->posted_by_user->renderName() : ''; 
	}

	public function renderApprovedBy() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}
	
	public function renderShowUrl() {
        return route('bank-reconciliations.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-reconciliations.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-reconciliations.restore', $this->id);
    }

}
