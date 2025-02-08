<?php

namespace App\Models\JournalLines;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\AdminSetups\Client;
use App\Models\PurchaseOrders\VendorPayment;

class VendorPaymentJournalVoucher extends Model
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
            'vendor_payment_journal_number' => $this->vendor_payment_journal_number,
            'invoice_journal_batch_number' => $this->invoice_journal_batch_number,
            'journal_name' => $this->journal_name,
            'voucher_line_number' => $this->voucher_line_number,
            'voucher_date' => $this->voucher_date,
            'balance_journal' => $this->balance_journal,
            'balance_journal_per_voucher' => $this->balance_journal_per_voucher,
            'total_debit_journal' => $this->total_debit_journal,
            'total_credit_journal' => $this->total_credit_journal,
            'total_debit_per_voucher' => $this->total_debit_per_voucher,
            'total_credit_per_voucher' => $this->total_credit_per_voucher,
            'debit_amount' => $this->debit_amount,
            'credit_amount' => $this->credit_amount,
            'description' => $this->description,
            'approved_date' => $this->approved_date,
            'reported_as_ready_by_journal' => $this->reported_as_ready_by_journal,
            'approved_by_journal' => $this->approved_by_journal,
            'rejected_by_journal' => $this->rejected_by_journal,
            'review_date_trans' => $this->review_date_tran,
            'approved_by_id_trans' => $this->approved_by_id_trans,
            'approved_by_name_trans' => $this->approved_by_name_trans,
            'posted_checkbox' => $this->posted_checkbox,
            'posted_on' => $this->posted_on,
            'posted_by' => $this->posted_by_user ? $this->posted_by_user->fullname : null,
            'posting_profile' => $this->posting_profile,
            'vendor_account' => $this->vendor_account,
            'vendor_name' => $this->vendor_name,
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'payment_due_date' => $this->payment_due_date,
            'settlement_type' => $this->settlement_type,
            'method_of_payment' => $this->method_of_payment,
            'terms_of_payment' => $this->terms_of_payment,
            'payment_id' => $this->payment_id,
            'payment_status' => $this->payment_status,
            'payment_specification' => $this->payment_specification,
            'payment_reference' => $this->payment_reference,
            'bank_transaction_type' => $this->bank_transaction_type,
            'bank_account' => $this->bank_account,
            'use_deposit_slip_checkox' => $this->use_deposit_slip_checkox,
            'deposit_slip_number' => $this->deposit_slip_number,
            'payment_reference' => $this->payment_reference,
            'postdated_check_status' => $this->postdated_check_status,
            'check_number' => $this->check_number,
            'check_number_issued' => $this->check_number_issued,
            'maturity_date' => $this->maturity_date,
            'received_date' => $this->received_date,
            'cashier' => $this->cashier,
            'salesperson' => $this->salesperson,
            'issuing_bank_branch' => $this->issuing_bank_branch,
            'issuing_bank_name' => $this->issuing_bank_name,
            'stop_payment' => $this->stop_payment,
            'replacement_check' => $this->replacement_check,
            'original_check' => $this->original_check,
            'check_amount' => $this->check_amount,
            'recipient_name' => $this->recipient_name,
            'main_account' => $this->main_account,
            'account_type' => $this->account_type,
            'offset_company_accounts' => $this->offset_company_accounts,
            'offset_account_type' => $this->offset_account_type,
            'offset_account' => $this->offset_account,
            'offset_transaction_text' => $this->offset_transaction_text,
            'sales_tax_direction' => $this->sales_tax_direction,
            'sales_tax_group' => $this->sales_tax_group,
            'item_sales_tax_group' => $this->item_sales_tax_group,
            'withholding_tax_group' => $this->withholding_tax_group,
            'fee_account' => $this->fee_account,
            'fee_id' => $this->fee_id,
            'fee_amount' => $this->fee_amount,
            'created_by' => $this->created_by_user ? $this->created_by_user->fullname : '',
            'updated_by' => $this->updated_by_user ? $this->updated_by_user->fullname : '',
        ];
    }

    /**
     * Relationships
     */
    
    public function client() 
    {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }
    
    public function posted_by_user()
    {
        return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
    }
    
    public function created_by_user()
    {
    	return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
    
    public function updated_by_user() 
    {
    	return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    public function offset_account_ma()
    {
        return $this->belongsTo(MainAccount::class, 'offset_account', 'id')->withTrashed();
    }

    public function vendor_payment()
    {
        return $this->belongsTo(VendorPayment::class, 'vendor_payment_number', 'vendor_payment_number')->withTrashed();
    }

    /**
     * Appends
    */

    public function getUpdateUrlAttribute() {
        return route('vendor-payment-journals.voucher-update', $this->id);
    }
}
