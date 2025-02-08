<?php

namespace App\Models\BankPostings;

use App\Extenders\Models\BaseModel as Model;

use App\Models\CashflowTransactions\CashflowTransactionAdjustment;
use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\Users\User;

class BankPosting extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'bank_transaction_posting' => $this->bank_transaction_posting,
			'description' => $this->description,
			'document' => $this->document,
			'bank_posting_code_number' => $this->bank_posting_code_number,
			'bank_posting' => $this->bank_posting,
	    ];
	}

	/**
	 * Relationships
	 */
	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	public function cash_register_adjustment() {
		return $this->belongsTo(CashflowTransactionAdjustment::class, 'cash_register_adjustment_id', 'id')->withTrashed();
	}

	public function bank_statement_line_adjustment() {
		return $this->belongsTo(BankAccountStatementLineAdjustment::class, 'bank_statement_line_adjustment_id', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'bank_transaction_posting',
		'description',
		'document',
		'bank_posting_code_number',
		'bank_posting',
		'cash_register_adjustment_id',
		'bank_statement_line_adjustment_id',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $item->update([
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

	/**
	 * Renders
	 */
	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('bank-postings.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-postings.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-postings.restore', $this->id);
    }
}
