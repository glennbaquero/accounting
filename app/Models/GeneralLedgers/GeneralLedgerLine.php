<?php

namespace App\Models\GeneralLedgers;

use App\Extenders\Models\BaseModel as Model;
use App\Models\JournalLines\InvoiceApprovalJournalVoucher;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\Journals\CustomerInvoiceJournal;
use App\Models\Journals\CustomerPaymentJournal;
use App\Models\Journals\InvoiceApprovalJournal;
use App\Models\Journals\VendorPaymentJournal;
use App\Models\MainAccounts\MainAccount;

class GeneralLedgerLine extends Model
{
    protected $table = 'general_ledger_journal_lines';

    public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	    ];
	}

    public function general_ledger() 
    {
        return $this->belongsTo(GeneralLedger::class)->withTrashed();
    }

    public function accrual_posting()
    {
        return $this->belongsTo(AccrualPosting::class, 'accrual_id')->withTrashed();
    }

    public function main_account_relation()
    {
        return $this->belongsTo(MainAccount::class, 'main_account')->withTrashed();
    }

    public function invoice_approval_journal_voucher()
    {
        return $this->belongsTo(InvoiceApprovalJournalVoucher::class, 'journal_voucher_id');
    }

    public function vendor_payment_journal_voucher()
    {
        return $this->belongsTo(VendorPaymentJournalVoucher::class, 'journal_voucher_id');
    }

    public function invoice_approval_journal_header()
    {
        return $this->belongsTo(InvoiceApprovalJournal::class, 'invoice_approval_journal_number', 'invoice_approval_journal_number' );
    }

    public function vendor_payment_journal_header() {

        return $this->belongsTo(VendorPaymentJournal::class, 'journal_header_id');

    }

    public function customer_approval_journal_voucher()
    {
        return $this->belongsTo(InvoiceApprovalJournalVoucher::class, 'journal_voucher_id');
    }

    public function customer_payment_journal_voucher()
    {
        return $this->belongsTo(VendorPaymentJournalVoucher::class, 'journal_voucher_id');
    }

    public function customer_approval_journal_header()
    {
        return $this->belongsTo(CustomerInvoiceJournal::class, 'journal_header_id')->withTrashed();
    }

    public function customer_payment_journal_header()
    {
        return $this->belongsTo(CustomerPaymentJournal::class, 'journal_header_id')->withTrashed();
    }

}
