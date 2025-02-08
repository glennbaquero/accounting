<?php

namespace App\Models\Invoices;

use App\Models\Users\User;

use App\Models\Vendors\Vendor;
use App\Models\MainAccounts\MainAccount;
use App\Models\JournalSetups\PaymentMethod;
use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\PurchaseOrders\VendorPayment;
use App\Models\Journals\InvoiceApprovalJournal;
use App\Models\JournalLines\InvoiceApprovalJournalVoucher;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\PostingProfile\VendorPostingProfile;
use App\Models\PostingProfile\VendorPostingProfileHeader;
use App\Models\Checks\Check;

class PurchaseDeliveryReceipt extends Model
{
    protected $casts = [
        'method_of_payment' => 'integer',
    ];

    protected $appends = ['approved_by_fullname', 'posted_by_fullname', 'invoice_by_fullname'];
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'purchase_order_number' => $this->purchase_order_number,
            'purchase_order_date' => $this->purchase_order_date,
            'delivery_date' => $this->delivery_date,
        ];
    }

    /**
     * Relationships
     */
    
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_account', 'vendor_account')->withTrashed();
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_number', 'purchase_order_number')->withTrashed();
    }

    public function cost_center() {
        return $this->belongsTo(FinancialDimensionValue::class, 'cost_center_id', 'id')->withTrashed();
    }

    public function department() {
        return $this->belongsTo(FinancialDimensionValue::class, 'department_id', 'id')->withTrashed();
    }
    
    public function expense_purpose() {
        return $this->belongsTo(FinancialDimensionValue::class, 'expense_purpose_id', 'id')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
    }

    public function invoiced_by_user() {
        return $this->belongsTo(User::class, 'invoiced_by', 'id')->withTrashed();
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

    public function cancelled_by_user() {
        return $this->belongsTo(User::class, 'cancelled_by', 'id')->withTrashed();
    }

    public function purchase_delivery_receipt_lines() {
        return $this->hasMany(PurchaseDeliveryReceiptLine::class, 'purchase_delivery_receipt_number', 'purchase_delivery_receipt_number');
    }

    public function payments() {
        return $this->hasMany(VendorPayment::class);
    }

    public function payments_with_trashed() {
        return $this->hasMany(VendorPayment::class)->withTrashed();
    }

    public function vouchers() {
        return $this->hasMany(InvoiceApprovalJournalVoucher::class, 'vendor_invoice_number', 'purchase_delivery_receipt_number');
    }

    public function vouchers_with_trash() {
        return $this->hasMany(InvoiceApprovalJournalVoucher::class, 'vendor_invoice_number', 'purchase_delivery_receipt_number')->withTrashed();
    }

    public function invoice_approval_journal() {
        return $this->hasOne(InvoiceApprovalJournal::class, 'vendor_invoice_number', 'purchase_delivery_receipt_number')->withTrashed();
    }

    public function payment_method() {
        return $this->belongsTo(VendorPaymentMethod::class, 'method_of_payment', 'method_of_payment_id')->withTrashed();
    }

    public function vendor_posting_profile() {
        return $this->belongsTo(VendorPostingProfileHeader::class, 'posting_profile_id')->withTrashed();
    }

    public function checks() {
        return $this->hasMany(Check::class, 'vendor_invoice_number', 'purchase_delivery_receipt_number');
    }



    /**
     *  Attributes
     */

    public function getApprovedByFullnameAttribute() {
        return $this->approved_by_user ? $this->approved_by_user->renderName() : '-';
    }

    public function getPostedByFullnameAttribute() {
        return $this->posted_by_user ? $this->posted_by_user->renderName() : '-';
    }

    public function getInvoiceByFullnameAttribute() {
        return $this->invoiced_by_user ? $this->invoiced_by_user : '-';
    }



    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['posting_profile_id', 'invoice_onhold_checkbox','purchase_delivery_receipt_number', 'purchase_order_number', 'product_receipt_number', 'vendor_account', 'invoice_account', 'payment_id', 'vendor_name', 'vendor_contact_id', 'invoice_date', 'invoiced_by', 'invoice_status', 'match_variance_type', 'variance_approved_checkbox', 'posted_invoice_checkbox', 'posting_date', 'posted_by', 'approved_date', 'approved_by', 'payment_due_date', 'invoice_payment_release_date', 'settlement_type', 'method_of_payment', 'terms_of_payment', 'payment_specification', 'bank_account', 'sales_tax_group', 'tax_exempt_number', 'prices_include_sales_tax_checkbox', 'ignore_calculated_sales_tax_checkbox', 'cash_discount_code', 'cash_discount_percentage', 'charges_group', 'update_quantity_type', 'total_data_quantity', 'total_data_weight', 'subtotal_amount', 'total_discount', 'total_charges', 'total_sales_tax', 'total_round_off', 'total_amount', 'cost_center_id', 'department_id', 'expense_purpose_id', 'posting_profile', 'accounting_distribution', 'created_by', 'updated_by', 'description', 'invoice_account_name' , 'delivery_date', 'prices_include_sales_tax_checkbox', 'ignore_calculated_sales_tax_checkbox', 'cash_discount', 'delivery_contact', 'delivery_address', 'delivery_term', 'charge_group', 'mode_of_delivery', 'cash_discount', 'client_id', 'transaction_type', 'settlement_type', 'vendor_address' , 'document', 'document_status', 'purchase_order_return_number', 'total_sales_vat_exclusive', 'less_discount', 'add_charge', 'add_vat', 'total_sales_vat_inclusive', 'less_withholding_tax', 'amount_due', 'vattable_sales', 'vat_exempt_sale', 'zero_rated_sales', 'vat_amount', 'total_amount_due', 'cash_amount', 'check_amount', 'deposit_amount', 'other_amount', 'total_amount_received', 'outstanding', 'tax_posting_id', 'payment_schedule_id', 'is_cancelled', 'cancelled_on', 'cancelled_by'])
    {
        $vars = $request->only($columns);
        $vars['approved_invoice_checkbox'] = $request->filled('approved_invoice_checkbox');
        $vars['prices_include_sales_tax_checkbox'] = $request->filled('prices_include_sales_tax_checkbox');
        $vars['ignore_calculated_sales_tax_checkbox'] = $request->filled('ignore_calculated_sales_tax_checkbox');

        $vars['company_id'] = auth()->user()->company_id;

        if (!$item) {
            $vars['invoice_onhold_checkbox'] = $request->filled('invoice_onhold_checkbox') ? true : false;
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    public function generateInvoiceJournal(
        InvoiceApprovalJournal $journal_entry, 
        $entry_type='debit_amount', 
        $line_column='approved_on'
    ) {
        $this->update([
            'posting_date' => now(),
            'posted_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'posted_invoice_checkbox' => true,
        ]);

        $lines = $this->vendor_invoice_lines()->whereNotNull($line_column)->get();
        
        $offset_posting_profile = $this->vendor_posting_profile->posting_lines->first();
        $summary_account_posting_profile = $this->vendor_posting_profile->posting_lines->first();

        $offset = $offset_posting_profile->offset_account;
        $summary_account = $summary_account_posting_profile->summary_account;

        if(MainAccount::withTrashed()->findOrFail($summary_account) == 'Merchandise inventory') {
            $entry_type = 'debit_amount';
        }

        if(MainAccount::withTrashed()->findOrFail($offset) == 'Accounts Payable') {
            $entry_type = 'credit_amount';
        }

        foreach ($lines as $key => $line) {

            $count = InvoiceApprovalJournal::find($journal_entry->id)->invoice_approval_journal_vouchers->count() + 1;
            $order_number = 'IAJV-' . now()->format('mdY').'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
            $invoice_number = $this->purchase_delivery_receipt_number;
            $payment_id = $this->payment_id ?? '---';


            $last_added_voucher = $journal_entry->invoice_approval_journal_vouchers()->latest()->first();

            if($last_added_voucher->main_account != '---') {
                $journal_entry->invoice_approval_journal_vouchers()->create([
                    'client_id' => $journal_entry->client_id,
                    'entry_pair_number' => $key + 1,
                    'invoice_voucher_number' => $order_number,
                    'invoice_approval_journal_number' => $journal_entry->invoice_approval_journal_number,
                    'invoice_journal_batch_number' => $journal_entry->invoice_journal_batch_number,
                    'journal_name' => $journal_entry->journal_name,
                    'voucher_line_number' => $line->line_number,
                    'voucher_date' => now(),
                    $entry_type => $line->amount,
                    'vendor_invoice_number' => $this->purchase_delivery_receipt_number,
                    'invoice_date' => now(),
                    'due_date' => $this->payment_due_date,
                    'vendor_account' => $this->vendor_account,
                    'vendor_name' => $this->vendor->fullname,
                    'payment_id' => $this->payment_id ?? $payment_id,
                    'method_of_payment' => $this->method_of_payment ?? 'N/A',
                    'terms_of_payment' => $this->terms_of_payment ?? 'N/A',
                    'bank_transaction_type' => '---',
                    'bank_account' => $this->bank_account ?? '---',
                    'payment_specification' => $this->payment_specification ?? 'N/A',
                    'payment_deposit_slip' => 'payment_deposit_slip',
                    'purchase_order' => $this->purchase_order ? $this->purchase_order->purchase_order_number : '---',
                    'main_account' => '---',
                    'account_type' => $entry_type == 'debit_amount' ? 'Vendor' : '---',
                    'offset_account_type' => $entry_type == 'credit_amount' ? $offset_posting_profile->offset_account_type : '---',
                    'offset_account' => $offset,
                    'charges_percentage' => $line->discount,
                    'cash_discount_code' => 'code',
                    'cash_discount_date' => now(),
                    'cash_discount_amount' => $line->discount_percentage,
                    'release_date_comment' => '---',
                    'tax_exempt_number' => $this->purchase_order ? $this->purchase_order->tax_exempt_number : null,
                    'calculated_sales_tax_amount' => 0,
                    'sales_tax_code' => '---',
                    'sales_tax_direction' => '---',
                    'sales_tax_group' => $line->sales_tax_group,
                    'item_sales_tax_group' => $line->item_sales_tax_group,
                    'actual_tax_amount' => 0,
                    
                    'created_by' => auth()->user()->fullname,

                    'updated_by' => null,
                    'invoice_number' => $invoice_number,
                    'invoice_journal_number' => $invoice_number,
                    
                    'reported_as_ready_by_journal' => null,
                    'approved_by_journal' => null,
                    'rejected_by_journal' => null,
                    'review_date_trans' => null,
                    'approved_by_id_trans' => null,
                    'approved_by_name_trans' => null,
                    'offset_company_accounts' => null,
                    'offset_transaction_text' => null,
                    'description' => $this->description,
                ]);
            } else {
                $journal_entry->invoice_approval_journal_vouchers()->create([
                    'client_id' => $journal_entry->client_id,
                    'entry_pair_number' => $key + 1,
                    'invoice_voucher_number' => $order_number,
                    'invoice_approval_journal_number' => $journal_entry->invoice_approval_journal_number,
                    'invoice_journal_batch_number' => $journal_entry->invoice_journal_batch_number,
                    'journal_name' => $journal_entry->journal_name,
                    'voucher_line_number' => $line->line_number,
                    'voucher_date' => now(),
                    $entry_type => $line->amount,
                    'vendor_invoice_number' => $this->purchase_delivery_receipt_number,
                    'invoice_date' => now(),
                    'due_date' => $this->payment_due_date,
                    'vendor_account' => $this->vendor_account,
                    'vendor_name' => $this->vendor->fullname,
                    'payment_id' => $this->payment_id ?? $payment_id,
                    'method_of_payment' => $this->method_of_payment ?? 'N/A',
                    'terms_of_payment' => $this->terms_of_payment ?? 'N/A',
                    'bank_transaction_type' => '---',
                    'bank_account' => $this->bank_account ?? '---',
                    'payment_specification' => $this->payment_specification ?? 'N/A',
                    'payment_deposit_slip' => 'payment_deposit_slip',
                    'purchase_order' => $this->purchase_order ? $this->purchase_order->purchase_order_number : '---',
                    'main_account' => '---',
                    'account_type' => $entry_type == 'debit_amount' ? 'Vendor' : '---',
                    'offset_account_type' => $entry_type == 'credit_amount' ? $offset_posting_profile->offset_account_type : '---',
                    'offset_account' => $summary_account,
                    'charges_percentage' => $line->discount,
                    'cash_discount_code' => 'code',
                    'cash_discount_date' => now(),
                    'cash_discount_amount' => $line->discount_percentage,
                    'release_date_comment' => '---',
                    'tax_exempt_number' => $this->purchase_order ? $this->purchase_order->tax_exempt_number : null,
                    'calculated_sales_tax_amount' => 0,
                    'sales_tax_code' => '---',
                    'sales_tax_direction' => '---',
                    'sales_tax_group' => $line->sales_tax_group,
                    'item_sales_tax_group' => $line->item_sales_tax_group,
                    'actual_tax_amount' => 0,
                    
                    'created_by' => auth()->user()->fullname,

                    'updated_by' => null,
                    'invoice_number' => $invoice_number,
                    'invoice_journal_number' => $invoice_number,
                    
                    'reported_as_ready_by_journal' => null,
                    'approved_by_journal' => null,
                    'rejected_by_journal' => null,
                    'review_date_trans' => null,
                    'approved_by_id_trans' => null,
                    'approved_by_name_trans' => null,
                    'offset_company_accounts' => null,
                    'offset_transaction_text' => null,
                    'description' => $this->description,
                ]);
            }

            

            $line->update([
                'posting_date' => now(),
                'posted_by' => auth()->user()->id
            ]);
        }
    }

    // /**
    //  * Methods
    //  */

    /**
     * Renderers
     */
    
    public function renderShowUrl() {
        return route('vendor-invoices.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('vendor-invoices.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('vendor-invoices.restore', $this->id);
    }
    
    public function renderApprovalUrl() {
        return route('vendor-invoices.approved', $this->id);
    }

    public function renderPostUrl() {
        return route('vendor-invoices.posted', $this->id);
    }

    public function renderCancelUrl() {
        return route('vendor-invoices.cancel', $this->id);
    }

    public function renderInvoiceApprovalJournalUrl() {
        return route('po-invoice-approval-journals.index');
    }

    public function renderCOP() {
        $amount = 0.00;
        $lines = $this->vendor_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('charge_on_purchase');
        }
        return number_format($amount, 2, '.', ',');
    }

    public function renderSubtotal() {
        $amount = 0.00;
        $lines = $this->vendor_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('unit_price') *  $lines->sum('quantity') ;
        }
        return number_format($amount, 2, '.', ',');
    }

    public function renderTotalDiscount() {
        $amount = 0.00;
        $lines = $this->vendor_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('discount');
        }
        return number_format($amount, 2, '.', ',');
    }

    public function renderTotalAmount() {
        $amount = 0.00;
        $lines = $this->vendor_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('amount') + $lines->sum('charge_on_purchase');
        }

        return number_format($amount, 2, '.', ',');
    }
}
