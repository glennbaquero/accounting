<?php

namespace App\Models\PurchaseOrders;

use Illuminate\Support\Facades\DB;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\Vendors\Vendor;
use App\Models\Invoices\VendorInvoice;
use App\Models\MainAccounts\MainAccount;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\AdminSetups\Client;
use App\Models\Journals\VendorPaymentJournal;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Checks\Check;
use App\Models\Deposits\Deposit;
use App\Models\PostingProfile\VendorPostingProfile;
use App\Models\PostingProfile\VendorPostingProfileHeader;

use Throwable;
use Exception;

class VendorPayment extends Model
{
	protected $casts = [
		'postdated_check_status_id' => 'integer',
		'bank_posting' => 'integer',
		'posting_profile' => 'integer',
	];
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
            'vendor_payment_number' => $this->vendor_payment_number,
	        'issue_date' => $this->issue_date,
	        'payment_release_date' => $this->payment_release_date,
	        'clearing_date' => $this->clearing_date,
	        'payment_due_date' => $this->payment_due_date,
	        'payee' => $this->payee,
	        'vendor_name' => $this->vendor_name,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
	    ];
    }

	/**
	 * Relationships
	 */

	public function vouchers()
	{
		return $this->hasMany(VendorPaymentJournalVoucher::class, 'payment_id', 'vendor_payment_number');
	}

	public function vouchers_with_trashed()
	{
		return $this->hasMany(VendorPaymentJournalVoucher::class, 'payment_id', 'vendor_payment_number')->withTrashed();
	}

	public function client() {
        return $this->belongsTo(Client::class);
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_account_id', 'id')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
    }

    public function cost_center() {
        return $this->belongsTo(FinancialDimensionValue::class, 'dimension_value_cost_center_id', 'id')->withTrashed();
    }

    public function department() {
        return $this->belongsTo(FinancialDimensionValue::class, 'dimension_value_department_id', 'id')->withTrashed();
    }
    
    public function expense_purpose() {
        return $this->belongsTo(FinancialDimensionValue::class, 'dimension_value_expense_purpose_id', 'id')->withTrashed();
    }

    public function method_of_payment() {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }

    public function vendor_invoice() {
        return $this->belongsTo(VendorInvoice::class)->withTrashed();
    }

    public function vendor_posting_profile() {
    	return $this->belongsTo(VendorPostingProfileHeader::class, 'posting_profile')->withTrashed();
    }

	public function cancelled_by_user() {
		return $this->belongsTo(User::class, 'cancelled_by', 'id')->withTrashed();
	}

	/**
	 * Setters
	*/
    
    public static function store($request, $item = null, $columns = [
		'vendor_invoice_id', 
    	'vendor_payment_number', 
    	'issue_date', 
    	'payment_release_date', 
    	'clearing_date', 
    	'due_date', 
    	'payee', 
    	'description', 
    	'payment_status', 
    	'approved_payment', 
    	'approved_date', 
    	'approved_by', 
    	'posted_payment', 
    	'posting_date', 
    	'sales_tax_group', 
    	'tax_exempt_group', 
    	'prices_included_sales_tax', 
    	'ignore_calculated_tax', 
    	'cash_discount_code', 
    	'cash_discount_percentage', 
    	'charges_group', 
    	'vendor_account_id', 
    	'vendor_account', 
    	'invoice_account', 
    	'vendor_name', 
    	'vendor_address', 
    	'vendor_contact_id', 
    	'dimension_value_cost_center_id', 
    	'dimension_value_department_id', 
    	'dimension_value_expense_purpose_id', 
    	'posting_profile', 
    	'accounting_distribution', 
    	'created_by', 
    	'updated_by', 
    	'settlement_type', 
    	'method_of_payment_id', 
    	'payment_specification', 
    	'payment_reference', 
    	'bank_transaction_type', 
    	'bank_account', 
    	'total_quantity', 
    	'total_discount', 
    	'total_cash_discount', 
    	'total_charges', 
    	'total_sales_tax', 
    	'total_round_off', 
    	'sub_total_amount', 
    	'total_amount', 
    	'postdated_check_status_id',
    	'check_number',
    	'check_number_issued',
    	'maturity_date',
    	'received_date',
    	'original_check',
    	'recepient_name',
    	'cashier',
    	'sales_person',
    	'issuing_bank_branch',
    	'issuing_bank_branch_name',
    	'check_amount',
    	'stop_payment',
    	'replacement_check', 
    	'client_id', 
    	'transaction_type' , 
    	'document', 
    	'document_status',
    	'vendor_bank_account',
		'check_id',
		'recipient_name',
		'original_check_number',
		'deposit_status',
		'deposit_slip_number',
		'deposit_amount',
		'deposit_date',
		'deposit_payment_checkbox',
		'bank_statement_id',
		'bank_statement_issued_date',
		'bank_posting',
		'bank_reason',
		'bank_reconciliation_id',
		'reconciled_date',
		'adjustment_date',

		'total_sales_vat_exclusive',
		'less_discount',
		'add_charge',
		'add_12_vat',
		'total_sales_vat_inclusive',
		'less_withholding_tax',
		'amount_due',
		'vatable_sales',
		'vatexempt_sales',
		'zero_rated_sales',
		'vat_amount',
		'total_amount_due',
		'cash_amount',
		'other_amount',
		'total_amount_receiveds',
		'total_vattable_sales_vat_exclusive',

    ]) {

    	DB::beginTransaction();

        $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

        if (! $item) {
            $item = static::create($vars);
            if($request->method_of_payment == 'Check Payment') {
            	$item->generateCheckParent($request);
            }else if($request->method_of_payment == 'Deposit Payment') {
            	$item->generateDepositParent($request);
            }
        } else {
            $item->update($vars);
        }
        
    	DB::commit();
        
        return $item;
    }

    public function generateCheckParent($request) {
    	if($this->check_id)
    		return false;

    	$request->merge([
    		'client_id' => $this->client_id, 
	    	'check_number' => $this->check_number, 
	    	'issue_date' => $this->check_number_issued, 
	    	'clearing_date' => $this->clearing_date, 
	    	'reconciled_date' => $this->reconciled_date, 
	    	'check_amount' => $this->check_amount, 
	    	'bank_posting_profile' => $this->posting_profile, 
	    	'payment_reference' => $this->payment_reference, 
	    	'postdated_check_status' => $this->postdated_check_status_id, 
	    	'reason_code' => $this->bank_reason, 
	    	'description' => $this->description, 
	    	'cost_center' => $this->dimension_value_cost_center_id, 
	    	'department' => $this->dimension_value_department_id, 
	    	'expense_purpose' => $this->dimension_value_expense_purpose_id, 
	    	'client_bank_account_number' => $this->bank_account, 
	    	'vendor_bank_account_number' => $this->vendor_bank_account, 
	    	'method_of_payment_vendor' => $this->method_of_payment_id, 
	    	'vendor_payment_status' => $this->payment_status, 
	    	'vendor_payment_id' => $this->vendor_payment_number, 
	    	'maturity_date' => $this->maturity_date, 
	    	'vendor_invoice_number' => $this->invoice_account, 
    	]);

    	$check = Check::store($request);

    	$this->update([
    		'check_id' => $check->check_id,
    	]);

    	return $check;
    }

    public function generateDepositParent($request) {
    	if($this->deposit_id)
    		return false;
    	

    	$request->merge([
    		'client_id' => $this->client_id, 
    		'client_bank_account_number' => $this->bank_account, 
    		'deposit_slip_number' => $this->deposit_slip_number, 
    		'issue_date' => $this->deposit_date, 
	    	'bank_posting_profile' => $this->posting_profile, 
	    	'payment_reference' => $this->payment_reference, 
	    	'reason_code' => $this->bank_reason, 
	    	'description' => $this->description, 
	    	'cost_center' => $this->dimension_value_cost_center_id, 
	    	'department' => $this->dimension_value_department_id, 
	    	'expense_purpose' => $this->dimension_value_expense_purpose_id, 
	    	'deposit_amount' => $this->deposit_amount, 
	    	'method_of_payment_vendor' => $this->method_of_payment_id, 
	    	'vendor_payment_status' => $this->payment_status, 
	    	'vendor_payment_id' => $this->vendor_payment_number, 
    	]);

    	$deposit = Deposit::store($request);

    	$this->update([
    		'deposit_id' => $deposit->deposit_slip_id,
    	]);

    	return $deposit;
    }

    // TODO: generate invoice payment journal...
	//       couldn't do at the moment, being rushed...
	/**
	 * @throws Exception
	 */
    public function createPaymentJournalEntry(
		VendorPaymentJournal $vendor_payment_journal_entry,
		$entry_type = 'debit_amount',
		$payment_line_type = 'approved_payment') 
	{
		try {
			DB::beginTransaction();
			$admin_id = auth()->user()->id;
			$name = User::find($admin_id)->renderName();

			$this->update([
				'posted_payment' => true,
				'posting_date' => now(),
				'posted_by_id' => $admin_id,
				'posted_by_name' => $name,
				'updated_by' => $admin_id
			]);


			$offset_posting_profile = $this->vendor_posting_profile->posting_lines->first();
			$summary_account_posting_profile = $this->vendor_posting_profile->posting_lines->first();

			$offset = $offset_posting_profile->offset_account;
			$summary_account = $summary_account_posting_profile->summary_account;

			$lines = $this->vendor_payment_lines()
						->where($payment_line_type, true)
						->get();
			foreach ($lines as $line) {
				$count = VendorPaymentJournalVoucher::count();
				$order_number = now()->format('mdY').'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
				$invoice_number = 'INVNUM'.str_pad($count, 4, '0', STR_PAD_LEFT);
				$payment_id = 'PYMNT_ID'.str_pad($count, 4, '0', STR_PAD_LEFT);

				$last_added_voucher = $vendor_payment_journal_entry->vendor_payment_journal_vouchers()->latest()->first();

				if($last_added_voucher->main_account != '---') {
					$voucher = $vendor_payment_journal_entry->vendor_payment_journal_vouchers()->create([
						'voucher_number' => $order_number,
						'vendor_payment_journal_number' => $vendor_payment_journal_entry->vendor_payment_journal_number,
						'invoice_journal_batch_number' => $vendor_payment_journal_entry->invoice_journal_batch_number,
						'journal_name' => $vendor_payment_journal_entry->journal_name,
						'voucher_line_number' => $line->payment_line_number,
						'voucher_date' => now(),
						$entry_type => $line->amount,
						'invoice_date' => now(),
						'payment_due_date' => $this->due_date,
						'payee' => $this->payee,
						'vendor_account' => $this->vendor_account,
						'vendor_name' => $this->vendor ? $this->vendor->fullname : '---',
						'payment_id' => $this->vendor_payment_number ?? $payment_id,
						'method_of_payment' => $this->method_of_payment_id ?? 'N/A',
						'terms_of_payment' => $this->terms_of_payment_id ?? 'N/A',
						'bank_transaction_type' => $this->bank_transaction_type,
						'bank_account' => $this->bank_account ?? '---',
						'issuing_bank_name' => $this->issuing_bank_branch_name,
						'payment_specification' => $this->payment_specification ?? 'N/A',
						'main_account' => MainAccount::first()->id,
						'account_type' => 'Vendor',
						'offset_account_type' => '---',
						'offset_account' => $offset,
						'sales_tax_direction' => '---',
						'sales_tax_group' => $line->sales_tax_group,
						'item_sales_tax_group' => $line->item_sales_tax_group,
						'payment_reference' => $this->payment_reference,
						'maturity_date' => $this->maturity_date,
						'received_date' => $this->received_date,
						'settlement_type' => $this->settlement_type,
						'check_amount' => $this->check_amount,
						'check_number' => $this->check_number,
						'check_number_issued' => $this->check_number_issued,
						'postdated_check_status' => $this->getPostdatedCheckStatus($this->postdated_check_status_id),
						'original_check' => $this->original_check,
						'replacement_check' => $this->replacement_check,
						
						'created_by' => auth()->user()->fullname,

						'updated_by' => null,
						'invoice_number' => $line->invoice_number ?? $invoice_number,
						
						'reported_as_ready_by_journal' => '---',
						'approved_by_journal'  => '---',
						'rejected_by_journal'  => '---',
						'approved_by_id_trans'  => '---',
						'approved_by_name_trans'  => '---',
						'offset_company_accounts'  => '---',
						'offset_transaction_text'  => '---',
						'description'  => '---',
					]);
				} else {
					$voucher = $vendor_payment_journal_entry->vendor_payment_journal_vouchers()->create([
						'voucher_number' => $order_number,
						'vendor_payment_journal_number' => $vendor_payment_journal_entry->vendor_payment_journal_number,
						'invoice_journal_batch_number' => $vendor_payment_journal_entry->invoice_journal_batch_number,
						'journal_name' => $vendor_payment_journal_entry->journal_name,
						'voucher_line_number' => $line->payment_line_number,
						'voucher_date' => now(),
						$entry_type => $line->amount,
						'invoice_date' => now(),
						'payment_due_date' => $this->due_date,
						'payee' => $this->payee,
						'vendor_account' => $this->vendor_account,
						'vendor_name' => $this->vendor ? $this->vendor->fullname : '---',
						'payment_id' => $this->vendor_payment_number ?? $payment_id,
						'method_of_payment' => $this->method_of_payment_id ?? 'N/A',
						'terms_of_payment' => $this->terms_of_payment_id ?? 'N/A',
						'bank_transaction_type' => $this->bank_transaction_type,
						'bank_account' => $this->bank_account ?? '---',
						'issuing_bank_name' => $this->issuing_bank_branch_name,
						'payment_specification' => $this->payment_specification ?? 'N/A',
						'main_account' => '---',
						'account_type' => 'Vendor',
						'offset_account_type' => '---',
						'offset_account' => $summary_account,
						'sales_tax_direction' => '---',
						'sales_tax_group' => $line->sales_tax_group,
						'item_sales_tax_group' => $line->item_sales_tax_group,
						'payment_reference' => $this->payment_reference,
						'maturity_date' => $this->maturity_date,
						'received_date' => $this->received_date,
						'settlement_type' => $this->settlement_type,
						'check_amount' => $this->check_amount,
						'check_number' => $this->check_number,
						'check_number_issued' => $this->check_number_issued,
						'postdated_check_status' => $this->getPostdatedCheckStatus($this->postdated_check_status_id),
						'original_check' => $this->original_check,
						'replacement_check' => $this->replacement_check,
						
						'created_by' => auth()->user()->fullname,

						'updated_by' => null,
						'invoice_number' => $line->invoice_number ?? $invoice_number,
						
						'reported_as_ready_by_journal' => '---',
						'approved_by_journal'  => '---',
						'rejected_by_journal'  => '---',
						'approved_by_id_trans'  => '---',
						'approved_by_name_trans'  => '---',
						'offset_company_accounts'  => '---',
						'offset_transaction_text'  => '---',
						'description'  => '---',
					]);
				}

				
				
				$line->update([
					'posting_by_id' => auth()->user()->id,
					'posting_date' => now(),
					'posting_by_name' => $name,
					'posted_payment' => true,
					'voucher_number' => $voucher->voucher_number
				]);
			}
			DB::commit();
		} catch(Throwable $e) {
			DB::rollBack();
			throw new Exception($e);
		}
	}
	
	public function getPostdatedCheckStatus($id) {
		// TODO: use table for lookup when that table is availble
		$temporary_lookup_table = [
			[ 'id' => 1, 'value' => 'Open' ],
			[ 'id' => 2, 'value' => 'On hold' ],
			[ 'id' => 3, 'value' => 'Paid' ],
			[ 'id' => 4, 'value' => 'Posted' ],
			[ 'id' => 5, 'value' => 'Cancelled' ]
		];
		return array_search($id, array_column($temporary_lookup_table, 'id', 'value'));
	}

	/**
	 * Setters
	*/

    public function renderShowUrl() {
        return route('vendor-payments.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('vendor-payments.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('vendor-payments.restore', $this->id);
    }

    public function renderApprovalUrl() {
        return route('vendor-payments.approved', $this->id);
    }

    public function renderPostUrl() {
        return route('vendor-payments.posted', $this->id);
    }

    public function renderCancelUrl() {
        return route('vendor-payments.posted', $this->id);
    }
}
