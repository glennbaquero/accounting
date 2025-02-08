<?php

namespace App\Models\Invoices;

use App\Models\Users\User;

use App\Models\Customers\Customer;
use App\Models\SalesOrders\SalesOrder;
use App\Models\MainAccounts\MainAccount;
use App\Models\SalesOrders\CustomerPayment;
use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Journals\CustomerInvoiceJournal;

use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\JournalLines\CustomerInvoiceApprovalVoucher;

use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\Checks\Check;

class CustomerInvoice extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'sales_order_number' => $this->sales_order_number,
	        'sales_order_date' => $this->sales_order_date,
	        'delivery_date' => $this->delivery_date,
	    ];
	}

	/**
	 * Relationships
	 */
	

    public function client()
    {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	public function customer()
    {
		return $this->belongsTo(Customer::class, 'customer_account', 'customer_account')->withTrashed();
	}

	public function sales_order()
    {
		return $this->belongsTo(SalesOrder::class, 'sales_order_number', 'sales_order_number')->withTrashed();
	}

	public function cost_center()
    {
        return $this->belongsTo(FinancialDimensionValue::class, 'cost_center_id', 'id')->withTrashed();
    }

    public function department()
    {
        return $this->belongsTo(FinancialDimensionValue::class, 'department_id', 'id')->withTrashed();
    }

    public function expense_purpose()
    {
        return $this->belongsTo(FinancialDimensionValue::class, 'expense_purpose_id', 'id')->withTrashed();
    }

	public function created_by_user()
    {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user()
    {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	public function approved_by_user()
    {
		return $this->belongsTo(User::class, 'approved_by', 'id')->withTrashed();
	}

	public function posted_by_user()
    {
		return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
	}

    public function invoice_by_user()
    {
        return $this->belongsTo(User::class, 'invoiced_by', 'id')->withTrashed();
    }

	public function customer_invoice_lines() 
    {
		return $this->hasMany(CustomerInvoiceLine::class, 'customer_invoice_number', 'customer_invoice_number');
	}

    public function payment_method() {
        return $this->belongsTo(VendorPaymentMethod::class, 'method_of_payment', 'method_of_payment_id')->withTrashed();
    }

    public function customer_posting_profile()
    {
        return $this->belongsTo(CustomerPostingProfile::class, 'posting_profile')->withTrashed();
    }

    public function payments() {
        return $this->hasMany(CustomerPayment::class);
    }

    public function checks() {
        return $this->hasMany(Check::class, 'customer_invoice_number', 'customer_invoice_number');
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['sales_order_number', 'customer_account', 'invoice_account', 'customer_name', 'customer_contact_id', 'invoice_date', 'invoiced_by', 'invoice_status', 'posting_date', 'approved_date', 'approved_by', 'payment_due_date', 'invoice_payment_release_date', 'cost_center_id', 'department_id', 'expense_purpose_id', 'posting_profile', 'accounting_distribution', 'created_by', 'updated_by', 'updated_at', 'method_of_payment', 'terms_of_payment', 'payment_specification', 'total_data_quantity', 'subtotal_amount', 'total_discount', 'total_charges', 'total_sales_tax', 'total_round_off', 'total_amount', 'total_cash_discount', 'client_id', 'transaction_type', 'settlement_type', 'customer_address', 'invoiced_user_entry', 'bank_account' , 'document', 'document_status', 'invoice_onhold_checkbox', 'sales_order_return_number', 'total_sales_vat_exclusive', 'less_discount', 'add_charge', 'add_vat', 'total_sales_vat_inclusive', 'less_withholding_tax', 'amount_due', 'vattable_sales', 'vat_exempt_sale', 'zero_rated_sales', 'vat_amount', 'total_amount_due', 'cash_amount', 'check_amount', 'deposit_amount', 'other_amount', 'total_amount_received', 'outstanding', 'tax_posting_id', 'payment_schedule_id', 'delivery_type', 'quantity_totals', 'delivery_remainder'])
	{
		$vars = $request->only($columns); 
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
            $vars['invoice_onhold_checkbox'] = $request->filled('invoice_onhold_checkbox') ? true : false;
			$vars['customer_invoice_number'] = $request->customer_invoice_number;
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);
	    }

	    return $item;
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('customer-invoices.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('customer-invoices.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('customer-invoices.restore', $this->id);
    }
    
    public function renderConfirmUrl() {
        return route('customer-invoices.confirmation', $this->id);
    }

    public function renderInvoiceApprovalJournalUrl() {

    	$exist = CustomerInvoiceJournal::where('cost_center', $this->sales_order->cost_center)->where('department', $this->sales_order->department)->where('expense_purpose', $this->sales_order->expense_purpose)->first();

    	if($exist) {
    		return $exist->renderCreateLineUrl();
    	}

        return route('so-invoice-approval-journals.index');
    }

    public function renderPostCustomerInvoiceUrl() {
    	return route('customer-invoices.posted', $this->id);
    }

    public function generateCustomerInvoiceJournal(
        CustomerInvoiceJournal $journal_entry, 
        $entry_type='debit_amount', 
        $line_column='approved_on'
    ) {
    	$this->update([
    	    'posting_date' => now(),
    	    'posted_by' => auth()->user()->id,
    	    'updated_by' => auth()->user()->id,
    	    'posted_invoice_checkbox' => true,
    	]);

    	$approved_lines = $this->customer_invoice_lines()->whereNotNull($line_column)->get();

        $offset = $this->customer_posting_profile->offset_account;
        $summary_account = $this->customer_posting_profile->summary_account;

        if(MainAccount::withTrashed()->findOrFail($summary_account) == 'Sales Revenue') {
            $entry_type = 'debit_amount';
        }

        if(MainAccount::withTrashed()->findOrFail($offset) == 'Accounts Receivable') {
            $entry_type = 'credit_amount';
        }

    	foreach ($approved_lines as $line) {
            $count = CustomerInvoiceApprovalVoucher::count();
            $order_number = now()->format('mdY').'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
            $invoice_number = 'INVNUM'.str_pad($count, 4, '0', STR_PAD_LEFT);
            $payment_id = 'PYMNT_ID'.str_pad($count, 4, '0', STR_PAD_LEFT);


            $last_added_voucher = $journal_entry->customer_invoice_approval_journal_vouchers()->latest()->first();

            if($last_added_voucher->main_account != '---') {
                $journal_entry->customer_invoice_approval_journal_vouchers()->create([
                    'invoice_voucher_number' => $order_number,
                    'customer_invoice_journal_number' => $journal_entry->customer_invoice_journal_number,
                    'invoice_journal_batch_number' => $journal_entry->invoice_journal_batch_number,
                    'journal_name' => $journal_entry->journal_name,
                    'voucher_line_number' => $line->line_number,
                    'voucher_date' => now(),
                    $entry_type => $line->amount,
                    'customer_invoice_number' => $this->customer_invoice_number,
                    'invoice_date' => now(),
                    'due_date' => $this->payment_due_date,
                    'customer_account' => $this->customer_account,
                    'customer_name' => $this->customer->fullname,
                    'payment_id' => $this->payment_id ?? $payment_id,
                    'method_of_payment' => $this->method_of_payment ?? 'N/A',
                    'terms_of_payment' => $this->terms_of_payment ?? 'N/A',
                    'bank_transaction_type' => 'Sample',
                    'bank_account' => $this->bank_account ?? 'sample',
                    'payment_specification' => $this->payment_specification ?? 'N/A',
                    'payment_deposit_slip' => 'payment_deposit_slip',
                    'sales_order' => $this->sales_order ? $this->sales_order->sales_order_number : null,
                    'main_account' => $summary_account,
                    'account_type' => $entry_type == 'debit_amount' ? $offset : '----',
                    'offset_account_type' => $entry_type == 'credit_amount' ? $this->customer_posting_profile->offset_account_type : '---',
                    'offset_account' => '---',
                    'charges_percentage' => $line->discount,
                    'cash_discount_code' => 'code',
                    'cash_discount_date' => now(),
                    'cash_discount_amount' => $line->discount_percentage,
                    'release_date_comment' => 'sample',
                    'tax_exempt_number' => $this->sales_order ? $this->sales_order->tax_exempt_number : null,
                    'calculated_sales_tax_amount' => 0,
                    'sales_tax_code' => 'sample',
                    'sales_tax_direction' => 'sample',
                    'sales_tax_group' => $line->sales_tax_group,
                    'item_sales_tax_group' => $line->item_sales_tax_group,
                    'actual_tax_amount' => 0,
                    
                    'created_by' => auth()->user()->fullname,

                    'updated_by' => null,
                    'invoice_number' => $invoice_number,
                    
                    'reported_as_ready_by_journal' => '---',
                    'approved_by_journal'  => '---',
                    'rejected_by_journal'  => '---',
                    'review_date_trans'  => '---',
                    'approved_by_id_trans'  => '---',
                    'approved_by_name_trans'  => '---',
                    'offset_company_accounts'  => '---',
                    'offset_transaction_text'  => '---',
                    'description'  => '---',
                ]);
            } else {
                $journal_entry->customer_invoice_approval_journal_vouchers()->create([
                    'invoice_voucher_number' => $order_number,
                    'customer_invoice_journal_number' => $journal_entry->customer_invoice_journal_number,
                    'invoice_journal_batch_number' => $journal_entry->invoice_journal_batch_number,
                    'journal_name' => $journal_entry->journal_name,
                    'voucher_line_number' => $line->line_number,
                    'voucher_date' => now(),
                    $entry_type => $line->amount,
                    'customer_invoice_number' => $this->customer_invoice_number,
                    'invoice_date' => now(),
                    'due_date' => $this->payment_due_date,
                    'customer_account' => $this->customer_account,
                    'customer_name' => $this->customer->fullname,
                    'payment_id' => $this->payment_id ?? $payment_id,
                    'method_of_payment' => $this->method_of_payment ?? 'N/A',
                    'terms_of_payment' => $this->terms_of_payment ?? 'N/A',
                    'bank_transaction_type' => 'Sample',
                    'bank_account' => $this->bank_account ?? 'sample',
                    'payment_specification' => $this->payment_specification ?? 'N/A',
                    'payment_deposit_slip' => 'payment_deposit_slip',
                    'sales_order' => $this->sales_order ? $this->sales_order->sales_order_number : null,
                    'main_account' => '---',
                    'account_type' => $entry_type == 'debit_amount' ? $offset : '----',
                    'offset_account_type' => $entry_type == 'credit_amount' ? $this->customer_posting_profile->offset_account_type : '---',
                    'offset_account' => $summary_account,
                    'charges_percentage' => $line->discount,
                    'cash_discount_code' => 'code',
                    'cash_discount_date' => now(),
                    'cash_discount_amount' => $line->discount_percentage,
                    'release_date_comment' => 'sample',
                    'tax_exempt_number' => $this->sales_order ? $this->sales_order->tax_exempt_number : null,
                    'calculated_sales_tax_amount' => 0,
                    'sales_tax_code' => 'sample',
                    'sales_tax_direction' => 'sample',
                    'sales_tax_group' => $line->sales_tax_group,
                    'item_sales_tax_group' => $line->item_sales_tax_group,
                    'actual_tax_amount' => 0,
                    
                    'created_by' => auth()->user()->fullname,

                    'updated_by' => null,
                    'invoice_number' => $invoice_number,
                    
                    'reported_as_ready_by_journal' => '---',
                    'approved_by_journal'  => '---',
                    'rejected_by_journal'  => '---',
                    'review_date_trans'  => '---',
                    'approved_by_id_trans'  => '---',
                    'approved_by_name_trans'  => '---',
                    'offset_company_accounts'  => '---',
                    'offset_transaction_text'  => '---',
                    'description'  => '---',
                ]);
            }

            

            $line->update([
                'posting_date' => now(),
                'posted_by' => auth()->user()->id
            ]);
    	}
    }

    public function renderCOP() {
        $amount = 0.00;
        $lines = $this->customer_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('charges_on_sales');
        }
        return number_format($amount, 2, '.', ',');
    }

    public function renderSubtotal() {
        $amount = 0.00;
        $lines = $this->customer_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('unit_price') *  $lines->sum('quantity') ;
        }
        return number_format($amount, 2, '.', ',');
    }

    public function renderTotalDiscount() {
        $amount = 0.00;
        $lines = $this->customer_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('discount');
        }
        return number_format($amount, 2, '.', ',');
    }

    public function renderTotalAmount() {
        $amount = 0.00;
        $lines = $this->customer_invoice_lines;
        
        if($lines->count()) {
            $amount = $lines->sum('amount') + $lines->sum('charges_on_sales');
        }

        return number_format($amount, 2, '.', ',');
    }
}
