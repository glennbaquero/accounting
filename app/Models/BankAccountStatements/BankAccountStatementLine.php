<?php

namespace App\Models\BankAccountStatements;

use App\Extenders\Models\BaseModel as Model;

use App\Models\AdminSetups\BankReason;
use App\Models\Checks\Check;
use App\Models\Deposits\Deposit;
use App\Models\CashflowTransactions\CashflowTransaction;
use App\Models\Users\User;

class BankAccountStatementLine extends Model
{
	protected $appends = [
		'check_number',
		'deposit_slip_number',
	];

	/**
	 * @Relationship
	 */
	public function statement() {
		return $this->belongsTo(BankAccountStatement::class, 'statement_id', 'bank_statement_id');
	}

	public function  bankReason() {
		return $this->belongsTo(BankReason::class, 'bank_reason', 'reason_code')->withTrashed();
	}

	public function check() {
		return $this->belongsTo(Check::class, 'check_id', 'id')->withTrashed();
	}

	public function deposit() {
		return $this->belongsTo(Check::class, 'deposit_id', 'id')->withTrashed();
	}

	public function getCheckNumberAttribute() {
		return $this->check ? $this->check->check_number : '---';
	}

	public function getDepositSlipNumberAttribute() {
		return $this->deposit ? $this->deposit->deposit_slip_number : '---';
	}

	public function cashRegisters() {
		return $this->hasMany(CashflowTransaction::class, 'payment_reference', 'payment_reference');
	}

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'line_number' => $this->line_number,
			'transaction_date' => $this->transaction_date,
			'payment_reference' => $this->payment_reference,
			'bank_transaction_code' => $this->bank_transaction_code,
			'bank_reason' => $this->bank_reason,
			'withdrawal_debit_amount' => $this->withdrawal_debit_amount,
			'deposit_credit_amount' => $this->deposit_credit_amount,
			'cost_center' => $this->cost_center,
			'department' => $this->department,
			'statement_id' => $this->statement_id,
	    ];
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'line_number',
		'transaction_date',
		'payment_reference',
		'bank_transaction_code',
		'withdrawal_debit_amount',
		'deposit_credit_amount',
		'bank_reason',
		
		'cost_center',
		'department',
		'statement_id',
		'description',
	])
	{

		$vars = $request->only($columns);

	    if (!$item) {
	        $item = static::create($vars);
	        $statement_line_id = 'statement-line-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'statement_line_id' => $statement_line_id,
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

	public function created_by_user() {
    	return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
    
    public function updated_by_user() {
    	return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('bank-account-statement-lines.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-account-statement-lines.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-account-statement-lines.restore', $this->id);
    }

    public function renderEndingBalance() {
		return isset($this->statement->ending_balance) ? $this->statement->ending_balance : 0;
	}
}
