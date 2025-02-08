<?php

namespace App\Models\Journals;

use App\Extenders\Models\BaseModel as Model;

use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\FinancialDimensions\FinancialDimensionValue;

use Carbon\Carbon;

class VendorPaymentJournal extends Model
{
	// protected $appends = [ 'is_selected', 'updateUrl', 'archiveUrl', 'showUrl', 'restoreUrl', 'totalDebit', 'totalCredit', 'totalBalance', 'validateUrl' ];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'vendor_payment_journal_number' => $this->vendor_payment_journal_number,
	        'invoice_journal_batch_number' => $this->invoice_journal_batch_number,
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

	/**
	 * Relationships
	 */
	
	public function vendor_payment_journal_vouchers() 
	{
		return $this->hasMany(VendorPaymentJournalVoucher::class, 'vendor_payment_journal_number', 'vendor_payment_journal_number');
	}
	
	public function department_fd() 
	{
		return $this->belongsTo(FinancialDimensionValue::class, 'department', 'financial_dimension_value_code')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['vendor_payment_journal_number', 'invoice_journal_batch_number', 'journal_name_number', 'journal_name', 'description', 'journal_status', 'balance_journal', 'total_debit_journal', 'total_credit_journal', 'reported_as_ready_by_journal', 'approved_by_journal', 'rejected_by_journal', 'posted_checkbox', 'posted_on', 'posted_by', 'log_in_checkbox', 'log_message', 'reversing_entry_checkbox', 'reversing_date', 'original_journal_number', 'show_user_created_only', 'journal_type', 'account_type', 'offset_account', 'document', 'detail_level', 'posting_layer', 'number_allocation_at_posting', 'delete_lines_after_posting', 'lines_limit', 'amounts_include_sales_tax', 'remittance_type', 'bank_account', 'protest_settlements', 'protest_settled_process', 'financial_dimensions', 'in_use_checkbox', 'used_by_user', 'locked_by_system', 'private_for_user_group', 'created_by', 'updated_by', 'cost_center', 'department', 'expense_purpose', 'updated_at', 'client_id', 'company_id', 'method_of_payment_id'])
	{
	  	$vars = $request->only($columns);
	    $vars['reversing_date'] = $request->filled('reversing_date') ? Carbon::parse($request->reversing_date) : null;
	    $vars['posted_checkbox'] = $request->filled('posted_checkbox');
	    $vars['log_in_checkbox'] = $request->filled('log_in_checkbox');
	    $vars['reversing_entry_checkbox'] = $request->filled('reversing_entry_checkbox');
	    $vars['show_user_created_only'] = $request->filled('show_user_created_only');
	    $vars['amounts_include_sales_tax'] = $request->filled('amounts_include_sales_tax');
	    $vars['in_use_checkbox'] = $request->filled('in_use_checkbox');
	    
	    $vars['company_id'] = auth()->user()->company_id;
	    
	    if (!$item) {
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);
	    }

	    return $item;
	}

	public function getVoucherLines() 
	{
		$vouchers = $this->vendor_payment_journal_vouchers;
		$data = [];

		foreach ($vouchers as $line) {
			array_push($data, [
				'id' => $line->id,
				'voucher_number' => $line->voucher_number,
				'vendor_payment_journal_number' => $line->vendor_payment_journal_number,
				'invoice_journal_batch_number' => $line->invoice_journal_batch_number,
				'journal_name' => $line->journal_name,
				'voucher_line_number' => $line->voucher_line_number,
				'voucher_date' => $line->voucher_date,
				'balance_journal' => $line->balance_journal,
				'balance_journal_per_voucher' => $line->balance_journal_per_voucher,
				'total_debit_journal' => $line->total_debit_journal,
				'total_credit_journal' => $line->total_credit_journal,
				'total_debit_per_voucher' => $line->total_debit_per_voucher,
				'total_credit_per_voucher' => $line->total_credit_per_voucher,
				'debit_amount' => $line->debit_amount,
				'credit_amount' => $line->credit_amount,
				'description' => $line->description,
				'approved_date' => $line->approved_date,
				'reported_as_ready_by_journal' => $line->reported_as_ready_by_journal,
				'approved_by_journal' => $line->approved_by_journal,
				'rejected_by_journal' => $line->rejected_by_journal,
				'review_date_trans' => $line->review_date_tran,
				'approved_by_id_trans' => $line->approved_by_id_trans,
				'approved_by_name_trans' => $line->approved_by_name_trans,
				'posted_checkbox' => $line->posted_checkbox,
				'posted_on' => $line->posted_on,
				'posted_by' => $line->posted_by_user ? $line->posted_by_user->fullname : null,
				'posting_profile' => $line->posting_profile,
				'vendor_account' => $line->vendor_account,
				'vendor_name' => $line->vendor_name,
				'invoice_number' => $line->invoice_number,
				'invoice_date' => $line->invoice_date,
				'payment_due_date' => $line->payment_due_date,
				'settlement_type' => $line->settlement_type,
				'method_of_payment' => $line->method_of_payment,
				'terms_of_payment' => $line->terms_of_payment,
				'payment_id' => $line->payment_id,
				'payment_status' => $line->payment_status,
				'payment_specification' => $line->payment_specification,
				'payment_reference' => $line->payment_reference,
				'bank_transaction_type' => $line->bank_transaction_type,
				'bank_account' => $line->bank_account,
				'use_deposit_slip_checkox' => $line->use_deposit_slip_checkox,
				'deposit_slip_number' => $line->deposit_slip_number,
				'payment_reference' => $line->payment_reference,
				'postdated_check_status' => $line->postdated_check_status,
				'check_number' => $line->check_number,
				'check_number_issued' => $line->check_number_issued,
				'maturity_date' => $line->maturity_date,
				'received_date' => $line->received_date,
				'cashier' => $line->cashier,
				'salesperson' => $line->salesperson,
				'issuing_bank_branch' => $line->issuing_bank_branch,
				'issuing_bank_name' => $line->issuing_bank_name,
				'stop_payment' => $line->stop_payment,
				'replacement_check' => $line->replacement_check,
				'original_check' => $line->original_check,
				'check_amount' => $line->check_amount,
				'recipient_name' => $line->recipient_name,
				'main_account' => $line->main_account,
				'account_type' => $line->account_type,
				'offset_company_accounts' => $line->offset_company_accounts,
				'offset_account_type' => $line->offset_account_type,
				'offset_account' => $line->offset_account,
				'offset_transaction_text' => $line->offset_transaction_text,
				'sales_tax_direction' => $line->sales_tax_direction,
				'sales_tax_group' => $line->sales_tax_group,
				'item_sales_tax_group' => $line->item_sales_tax_group,
				'withholding_tax_group' => $line->withholding_tax_group,
				'fee_account' => $line->fee_account,
				'fee_id' => $line->fee_id,
				'fee_amount' => $line->fee_amount,
				'created_by' => $line->created_by,
				'created_date' =>  $line->created_at ? Carbon::parse($line->created_at)->format('M. d, Y') : '---',
				'updated_date' =>  $line->updated_at ? Carbon::parse($line->updated_at)->format('M. d, Y') : '---',
				'updated_by' => $line->updated_by,
				'alreadyInSelectedItem' => false,

				'selected' => false,
				'updateUrl' => $line->updateUrl
			]);
		}

		return $data;
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('vendor-payment-journals.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('vendor-payment-journals.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('vendor-payment-journals.restore', $this->id);
    }

    public function renderCreateLineUrl() {
    	return route('vendor-payment-journals.create', $this->id);
    }

    public function renderValidateUrl() {
    	return route('vendor-payment-journals.validate', $this->id);
    }

    public function renderUpdateUrl() {
    	return route('vendor-payment-journals.validate', $this->id);
    }
    
    public function renderEditUrl() {
    	return route('vendor-payment-journals.edit', $this->id);
    }
    
    /**
     * Appends
     */
    
    // public function getIsSelectedAttribute() {
    // 	return false;
    // }

    // public function getUpdateUrlAttribute() {
    // 	return route('vendor-payment-journals.update', $this->id);
    // }

    // public function getShowUrlAttribute() {
    // 	return $this->renderShowUrl();
    // }

    // public function getArchiveUrlAttribute() {
    // 	return $this->renderArchiveUrl();
    // }

    // public function getRestoreUrlAttribute() {
    // 	return $this->renderRestoreUrl();
    // }
    
    // public function getValidateUrlAttribute() {
    // 	return route('vendor-payment-journals.validate', $this->id);
    // }

    // public function getTotalCreditAttribute() {
    // 	return number_format($this->vendor_payment_journal_vouchers->sum('credit_amount'), 2, '.', ',');
    // }

    // public function getTotalDebitAttribute() {
    // 	return number_format($this->vendor_payment_journal_vouchers->sum('debit_amount'), 2, '.', ',');
    // }

    // public function getTotalBalanceAttribute() {
    // 	return number_format($this->vendor_payment_journal_vouchers->sum('balance_journal'), 2, '.', ',');
    // }
}
