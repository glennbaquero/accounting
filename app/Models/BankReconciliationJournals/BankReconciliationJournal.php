<?php

namespace App\Models\BankReconciliationJournals;

use App\Extenders\Models\BaseModel as Model;

use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Users\User;
use Carbon\Carbon;

class BankReconciliationJournal extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'bank_reconciliation_journal_number' => $this->bank_reconciliation_journal_number,
	        'journal_batch_number' => $this->journal_batch_number,
	        'journal_name' => $this->journal_name,
	        'journal_status' => $this->journal_status,
	        'journal_type' => $this->journal_type,
	        'account_type' => $this->account_type,
	        'department' => $this->department_fd ? $this->department_fd->dimension_name : '',
	        'lines_limit' => $this->lines_limit,
	        'original_journal_number' => $this->original_journal_number,
	        'financial_dimensions' => $this->financial_dimensions,
	        'created_by' => $this->created_by,
	        'cost_center' => $this->cost_center,
	        'expense_purpose' => $this->expense_purpose,
	        'updated_by' => $this->updated_by,
	        'used_by_user' => $this->used_by_user,
	    ];
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

	public function rejected_by_user() {
		return $this->belongsTo(User::class, 'rejected_by', 'id')->withTrashed();
	}

	public function posted_by_user() {
		return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
	}

	public function log_by_user() {
		return $this->belongsTo(User::class, 'log_by', 'id')->withTrashed();
	}

	public function vouchers() {
		return $this->hasMany(BankReconciliationJournalVoucher::class, 'bank_reconciliation_journal_id', 'id')->withTrashed();
	}


	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'journal_batch_number',
		'journal_name_number',
		'journal_name',
		'description',
		'journal_status',
		'balance_journal',
		'total_debit_journal',
		'total_credit_journal',
		'reported_as_ready_by_journal',
		// 'reversing_entry_checkbox',
		// 'reversing_date',
		'original_journal_number',
		// 'show_user_created_only',
		'journal_type',
		'account_type',
		'offset_account',
		'document',
		'lines_limit',
		// 'amounts_include_sales_tax',
		'remittance_type',
		'bank_account',
		'cost_center',
		'department',
		'financial_dimension',
		// 'in_use_checkbox',
		'used_by_user',
		'locked_by_system',
		'private_for_user_group',
		'expense_purpose',
		// 'approved_by',
		// 'approved_date',
		// 'rejected_by',
		// 'posted_checkbox',
		// 'posted_on',
		// 'posted_by',
		// 'log_in_checkbox',
		// 'log_by',
		// 'log_date',
		// 'log_message',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

		$vars['reversing_date'] = $request->filled('reversing_date') ? Carbon::parse($request->reversing_date) : null;
	    $vars['posted_checkbox'] = $request->filled('posted_checkbox');
	    $vars['log_in_checkbox'] = $request->filled('log_in_checkbox');
	    $vars['reversing_entry_checkbox'] = $request->filled('reversing_entry_checkbox');
	    $vars['show_user_created_only'] = $request->filled('show_user_created_only');
	    $vars['amounts_include_sales_tax'] = $request->filled('amounts_include_sales_tax');
	    $vars['in_use_checkbox'] = $request->filled('in_use_checkbox');

	    if (!$item) {
	        $item = static::create($vars);
	        $bank_reconciliation_journal_number = 'reconciliation-journal-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        	'bank_reconciliation_journal_number' => $bank_reconciliation_journal_number,
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

	public function renderApprovedBy() {
		return $this->approved_by ? $this->approved_by->renderName() : ''; 
	}

	public function renderRejectedBy() {
		return $this->rejected_by ? $this->rejected_by->renderName() : ''; 
	}

	public function renderPostedBy() {
		return $this->posted_by ? $this->posted_by->renderName() : ''; 
	}

	public function renderLogBy() {
		return $this->log_by ? $this->log_by->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('bank-reconciliation-journals.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-reconciliation-journals.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-reconciliation-journals.restore', $this->id);
    }
}
