<?php

namespace App\Models\BankReconciliations;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\CashflowTransaction\CashflowTransactionAdjustment;
use App\Models\BankPostings\BankPosting;

class BankReconciliationLine extends Model
{
	protected $casts = [
		'bank_posting' => 'integer',
	];
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'bank_reconciliation_line_id' => $this->bank_reconciliation_line_id,
			'bank_reconciliation_id' => $this->bank_reconciliation_id,
			'posted_date' => $this->posted_date,
			'posted_by' => $this->posted_by,
			'posted_checkbox' => $this->posted_checkbox,
			'approved_date' => $this->approved_date,
			'approved_by' => $this->approved_by,
			'approved_checkbox' => $this->approved_checkbox,
			'description' => $this->description,
			'operation_type' => $this->operation_type,
			'source' => $this->source,
			'statement_adjustment_id' => $this->statement_adjustment_id,
			'cash_register_adjustment_id' => $this->cash_register_adjustment_id,
			'bank_posting_id' => $this->bank_posting_id,
			'adjustment_name' => $this->adjustment_name,
			'amount' => $this->amount,
	    ];
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'bank_reconciliation_line_id',
		'bank_reconciliation_id',
		'description',
		'operation_type',
		'source',
		'statement_adjustment_id',
		'cash_register_adjustment_id',
		'bank_posting_id',
		'adjustment_name',
		'adjustment_amount',
	])
	{

		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $bank_reconciliation_line_id = 'BA-reconciliation-line-id-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'bank_reconciliation_line_id' => $bank_reconciliation_line_id,
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

	public function posted_by_user() {
		return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
	}

	public function bank_reconciliation() {
		return $this->belongsTo(BankReconciliation::class, 'bank_reconciliation_id', 'bank_reconciliation_id')->withTrashed();
	}

	public function statement_adjustment() {
		return $this->belongsTo(BankAccountStatementLineAdjustment::class, 'statement_adjustment_id', 'bank_statement_adjustment_id')->withTrashed();
	}

	public function cash_register_adjustment() {
		return $this->belongsTo(BankAccountStatementLineAdjustment::class, 'cash_register_adjustment_id', 'cashflow_adjustment_id')->withTrashed();
	}


	public function bank_posting() {
		return $this->belongsTo(BankPosting::class, 'bank_posting_id', 'id')->withTrashed();
	}

	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderPostedBy() {
		return $this->posted_by_user ? $this->posted_by_user->renderName() : ''; 
	}

	public function renderApprovedBy() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}

	public function renderUpdateUrl() {
        return route('bank-reconciliation-lines.update', $this->id);
    }

	public function renderShowUrl() {
        return route('bank-reconciliation-lines.fetch-item', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-reconciliation-lines.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-reconciliation-lines.restore', $this->id);
    }

}
