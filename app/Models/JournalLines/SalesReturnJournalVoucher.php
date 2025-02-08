<?php

namespace App\Models\JournalLines;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\AdminSetups\Client;
use App\Models\Journals\SalesOrderReturnJournal;

class SalesReturnJournalVoucher extends Model
{
    protected $appends = [ 'updateUrl'];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'voucher_number' => $this->voucher_number,
	        'journal_batch_number' => $this->journal_batch_number,
	        'journal_name' => $this->journal_name,
	        'voucher_line_number' => $this->voucher_line_number,
	        'voucher_date' => $this->voucher_date,
	        'balance_journal' => $this->balance_journal,
	        'balance_journal_per_voucher' => $this->balance_journal_per_voucher,
	        'total_debit_journal' => $this->total_debit_journal,
	        'total_credit_journal' => $this->total_credit_journal,
	        'total_debit_per_voucher' => $this->total_debit_per_voucher,
	        'total_credit_per_voucher' => $this->total_credit_per_voucher,
	        'description' => $this->description,
	        'debit_amount' => $this->debit_amount,
	        'credit_amount' => $this->credit_amount,
	        'approved_date' => $this->approved_date,
	        'reported_as_ready_by_journal' => $this->reported_as_ready_by_journal,
	        'approved_by_journal' => $this->approved_by_journal,
	        'rejected_by_journal' => $this->rejected_by_journal,
	        'review_date_trans' => $this->review_date_trans,
	        'approved_by_id_trans' => $this->approved_by_id_trans,
	        'approved_by_name_trans' => $this->approved_by_name_trans,
	        'posted_checkbox' => $this->posted_checkbox,
	        'posted_on' => $this->posted_on,
	        'posted_by' => $this->posted_by,
	        'customer_invoice_number' => $this->customer_invoice_number,
	        'invoice_number' => $this->invoice_number,
	        'invoice_date' => $this->invoice_date,
	        'due_date' => $this->due_date,
	        'invoice_payment_release_date' => $this->invoice_payment_release_date,
	        'pending_customer_invoice' => $this->pending_customer_invoice,
	        'customer_account' => $this->customer_account,
	        'customer_name' => $this->customer_name,
	        'payment_id' => $this->payment_id,
	        'method_of_payment' => $this->method_of_payment,
	        'terms_of_payment' => $this->terms_of_payment,
	        'bank_transaction_type' => $this->bank_transaction_type,
	        'bank_account' => $this->bank_account,
	        'payment_specification' => $this->payment_specification,
	        'payment_deposit_slip' => $this->payment_deposit_slip,
	        'sales_order' => $this->sales_order,
	        'main_account' => $this->main_account,
	        'account_type' => $this->account_type,
	        'offset_company_accounts' => $this->offset_company_accounts,
	        'offset_account_type' => $this->offset_account_type,
	        'offset_account' => $this->offset_account,
	        'offset_transaction_text' => $this->offset_transaction_text,
	        'charges_percentage' => $this->charges_percentage,
	        'cash_discount_code' => $this->cash_discount_code,
	        'cash_discount_date' => $this->cash_discount_date,
	        'cash_discount_amount' => $this->cash_discount_amount,
	        'release_date_comment' => $this->release_date_comment,
	        'tax_exempt_number' => $this->tax_exempt_number,
	        'sales_tax_included_in_amount' => $this->sales_tax_included_in_amount,
	        'calculated_sales_tax_amount' => $this->calculated_sales_tax_amount,
	        'sales_tax_code' => $this->sales_tax_code,
	        'sales_tax_direction' => $this->sales_tax_direction,
	        'sales_tax_group' => $this->sales_tax_group,
	        'item_sales_tax_group' => $this->item_sales_tax_group,
	        'actual_tax_amount' => $this->actual_tax_amount,
	        'created_by' => $this->created_by,
	        'updated_by' => $this->updated_by,
	    ];
	}

	
	/**
	 * Relationships
	 */
	
	
	public function client() 
	{
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}
	
	public function vouchers() {
	    return $this->morphOne(Voucher::class, 'voucher');
	}

	public function posted_by_user() {
	    return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
	}

	public function offset_account_ma() {
	    return $this->belongsTo(MainAccount::class, 'offset_account', 'id')->withTrashed();
	}
	
	public function header() {
	    return $this->hasOne(SalesOrderReturnJournal::class, 'journal_number', 'journal_number')->withTrashed();
	}

	/**
	 * Appends
	*/

	public function getUpdateUrlAttribute() {
		return route('sales-return-journals.voucher-update', $this->id);
	}
}
