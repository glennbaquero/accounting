<?php

namespace App\Models\Journals;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\JournalLines\InventoryJournalVoucher;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\MainAccounts\MainAccount;
use Carbon\Carbon;

class InventoryJournal extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'inventory_journal_number' => $this->inventory_journal_number,
	        'inventory_journal_batch_number' => $this->inventory_journal_batch_number,
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
	
	public function inventory_journal_vouchers() 
	{
		return $this->hasMany(InventoryJournalVoucher::class, 'inventory_journal_number', 'inventory_journal_number');
	}

	public function department_fd() 
	{
		return $this->belongsTo(FinancialDimensionValue::class, 'department', 'financial_dimension_value_code')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['inventory_journal_number', 'invoice_journal_batch_number', 'journal_name_number', 'journal_name', 'description', 'journal_status', 'balance_journal', 'total_debit_journal', 'total_credit_journal', 'reported_as_ready_by_journal', 'approved_by_journal', 'rejected_by_journal', 'posted_on', 'posted_by', 'log_message', 'reversing_date', 'original_journal_number', 'journal_type', 'account_type', 'offset_account', 'document', 'detail_level', 'posting_layer', 'number_allocation_at_posting', 'delete_lines_after_posting', 'lines_limit', 'remittance_type', 'bank_account', 'protest_settlements', 'protest_settled_process', 'financial_dimensions', 'used_by_user', 'locked_by_system', 'private_for_user_group', 'created_by', 'updated_by', 'cost_center', 'department', 'expense_purpose', 'updated_at', 'client_id', 'company_id'])
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
		$vouchers = $this->inventory_journal_vouchers;
		$data = [];

		foreach ($vouchers as $line) {
			array_push($data, [
				'inventory_voucher_number' => $line->inventory_voucher_number,
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
				'description' => $line->description ?? '---',
				'debit_amount' => $line->debit_amount,
				'credit_amount' => $line->credit_amount,
				'approved_date' => $line->approved_date ,
				'reported_as_ready_by_journal' => $line->reported_as_ready_by_journal,
				'approved_by_journal' => $line->approved_by_journal,
				'rejected_by_journal' => $line->rejected_by_journal,
				'review_date_trans' => $line->review_date_trans,
				'approved_by_id_trans' => $line->approved_by_id_trans,
				'approved_by_name_trans' => $line->approved_by_name_trans,
				'posted_checkbox' => $line->posted_checkbox,
				'posted_on' => $line->posted_on,
				'posted_by' => $line->posted_by,
				'customer_invoice_number' => $line->customer_invoice_number,
				'invoice_number' => $line->invoice_number,
				'invoice_date' => $line->invoice_date,
				'due_date' => $line->due_date,
				'invoice_payment_release_date' => $line->invoice_payment_release_date,
				'pending_customer_invoice' => $line->pending_customer_invoice,
				'customer_account' => $line->customer_account,
				'customer_name' => $line->customer_name,
				'payment_id' => $line->payment_id,
				'method_of_payment' => $line->method_of_payment,
				'terms_of_payment' => $line->terms_of_payment,
				'bank_transaction_type' => $line->bank_transaction_type,
				'bank_account' => $line->bank_account,
				'payment_specification' => $line->payment_specification,
				'payment_deposit_slip' => $line->payment_deposit_slip,
				'sales_order' => $line->sales_order,
				'main_account' => $line->main_account,
				'account_type' => $line->account_type,
				'offset_company_accounts' => $line->offset_company_accounts,
				'offset_account_type' => $line->offset_account_type,
				'offset_account' => $line->offset_account,
				'offset_transaction_text' => $line->offset_transaction_text,
				'charges_percentage' => $line->charges_percentage,
				'cash_discount_code' => $line->cash_discount_code,
				'cash_discount_date' => $line->cash_discount_date,
				'cash_discount_amount' => $line->cash_discount_amount,
				'release_date_comment' => $line->release_date_comment,
				'tax_exempt_number' => $line->tax_exempt_number,
				'calculated_sales_tax_amount' => $line->calculated_sales_tax_amount,
				'sales_tax_code' => $line->sales_tax_code,
				'sales_tax_direction' => $line->sales_tax_direction,
				'sales_tax_group' => $line->sales_tax_group,
				'item_sales_tax_group' => $line->item_sales_tax_group,
				'actual_tax_amount' => $line->actual_tax_amount,
				'created_by' => $line->created_by,
				'created_date' =>  $line->created_at ? Carbon::parse($line->created_at)->format('M. d, Y') : '---',
				'updated_date' =>  $line->updated_at ? Carbon::parse($line->updated_at)->format('M. d, Y') : '---',
				'updated_by' => $line->updated_by,
				'alreadyInSelectedItem' => false,
				'updateUrl' => $line->updateUrl,

				'selected' => false
			]);
		}

		return $data;
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('inventory-journals.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('inventory-journals.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('inventory-journals.restore', $this->id);
    }
    
    public function renderCreateLineUrl() {
    	return route('inventory-journals.create', $this->id);
    }

    public function renderValidateUrl() {
    	return route('inventory-journals.validate', $this->id);
    }

    public function renderUpdateUrl() {
    	return route('inventory-journals.validate', $this->id);
    }
    
    public function renderEditUrl() {
    	return route('inventory-journals.edit', $this->id);
    }

}
