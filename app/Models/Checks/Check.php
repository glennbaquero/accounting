<?php

namespace App\Models\Checks;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\CustomerBankAccount;
use App\Models\Vendors\VendorBankAccount;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\AdminSetups\Client;

use Carbon\Carbon;

class Check extends Model
{
	protected $appends = [
		'client_bank_account_holder',
		'client_bank_account_type',
		'client_bank_name',
		'client_bank_branch',
		'client_bank_account_expiry',

		'customer_bank_account_holder',
		'customer_bank_account_type',
		'customer_bank_name',
		'customer_bank_branch',
		'customer_bank_account_expiry',

		'vendor_bank_account_holder',
		'vendor_bank_account_type',
		'vendor_bank_name',
		'vendor_bank_branch',
		'vendor_bank_account_expiry',

		'customer_company',
		'customer_contact',

		'approved_invoice_checkbox',
		'posted_invoice_checkbox',
		'posting_date',
		'posted_by',
	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'client' => $this->client->name,
			'client_bank_account_number' => $this->client_bank_account_number,
			'customer_bank_account_number' => $this->customer_bank_account_number,
			'check_id' => $this->check_id,
			'check_number' => $this->check_number,
			'issue_date' => $this->issue_date,
			'clearing_date' => $this->clearing_date,
			'reconciled_date' => $this->reconciled_date,
			'check_currency' => $this->check_currency,
			'check_amount' => $this->check_amount,
			'bank_posting_profile' => $this->bank_posting_profile,
			'method_of_payment_customer' => $this->method_of_payment_customer,
			'payment_reference' => $this->payment_reference,
			'payment_id' => $this->payment_id,
			'voucher_no' => $this->voucher_no,
			'canceled' => $this->canceled,
			'reason_code' => $this->reason_code,
			'reason_comment' => $this->reason_comment,
			'description' => $this->description,
			'postdated_check_status' => $this->postdated_check_status,
			'approved_date' => $this->approved_date,
			'approved_by' => $this->approved_by,
			'cost_center' => $this->cost_center,
			'department' => $this->department,
			'expense_purpose' => $this->expense_purpose,
			'created_by' => $this->created_by,
			'updated_by' => $this->updated_by,
	    ];
	}

	/**
	 * Relationships
	 */
	public function client() {
		return $this->belongsTo(Client::class)->withTrashed();
	}

	public function vendor_bank_account() {
		return $this->belongsTo(VendorBankAccount::class, 'vendor_bank_account_number', 'bank_account')->withTrashed();
	}

	public function client_bank_account() {
		return $this->belongsTo(ClientBankAccount::class, 'client_bank_account_number', 'bank_account')->withTrashed();
	}

	public function customer_bank_account() {
		return $this->belongsTo(CustomerBankAccount::class, 'customer_bank_account_number', 'bank_account')->withTrashed();
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

	public function voucher() {
		return $this->belongsTo(VendorPaymentJournalVoucher::class, 'voucher_no', 'voucher_number')->withTrashed();
	}

	public function cancelled_by_user() {
		return $this->belongsTo(User::class, 'cancelled_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'check_id',
		'check_number',
		'issue_date',
		'clearing_date',
		'reconciled_date',
		'check_currency',
		'check_amount',
		'bank_posting_profile',
		'payment_reference',
		'payment_id',
		'voucher_no',
		'postdated_check_status',
		'canceled',
		'reason_code',
		'reason_comment',
		'description',
		'cost_center',
		'department',
		'expense_purpose',
		'vendor_company',
		'vendor_contact',
		
		'client_bank_account_number',
		'customer_bank_account_number',
		'vendor_bank_account_number',

		'method_of_payment_customer',
		'method_of_payment_vendor',
		
		'vendor_payment_status',
		'customer_payment_status',

		'customer_payment_id',
		'vendor_payment_id',

		'maturity_date',
		'vendor_invoice_number',
		'customer_invoice_number',
	])
	{

		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	    	$vars['check_id'] = uniqid();
	        $item = static::create($vars);
	        $check_id = 'check-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'check_id' => $check_id,
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        ]);
	    } else {
	        $item->update($vars);
	        $item->update([
	        	'updated_by' => $request->user()->id,
	        ]);
	    }

	    if($request->filled('postdated_check_status')) {
	    	if($request->postdated_check_status != 'Cancelled') {
	    		$item->update(['canceled' => false]);
	    	}else {
	    		$item->update(['canceled' => true]);
	    	}

	    	if($item->voucher) {
	    		$item->voucher->update(['postdated_check_status' => $request->postdated_check_status]);
	    	}
	    }

	    return $item;
	}

	public function markCanceled() {
		if($this->approved_date) {
			return false;
		}

		if($this->voucher) {
			$this->voucher->update(['postdated_check_status' => 'Cancelled']);
		}

		return $this->update(['canceled' => true, 'postdated_check_status' => 'Cancelled']);
	}

	public function markApproved($request) {
		if($this->canceled) {
			return false;
		}

		return $this->update([
			'approved_date' => now(),
			'approved_by' => $request->user()->id,
		]);
	}


	public function getCustomerCompanyAttribute() {
		return $this->customer_bank_account ? $this->customer_bank_account->customer_company : '';
	}

	public function getCustomerContactAttribute() {
		return $this->customer_bank_account ? $this->customer_bank_account->customer_contact : '';
	}

	// Client Bank

	public function getClientBankAccountHolderAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->account_holder : '---';
	}

	public function getClientBankAccountTypeAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->bank_account_type : '---';
	}

	public function getClientBankNameAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->bank_name : '---';
	}

	public function getClientBankBranchAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->bank_branch : '---';
	}

	public function getClientBankAccountExpiryAttribute() {
		return $this->client_bank_account ? $this->client_bank_account->expiration_date : '---';
	}

	// Vendor Bank

	public function getVendorBankAccountHolderAttribute() {
		return $this->vendor_bank_account ? $this->vendor_bank_account->account_holder : '---';
	}

	public function getVendorBankAccountTypeAttribute() {
		return $this->vendor_bank_account ? $this->vendor_bank_account->bank_account_type : '---';
	}

	public function getVendorBankNameAttribute() {
		return $this->vendor_bank_account ? $this->vendor_bank_account->bank_name : '---';
	}

	public function getVendorBankBranchAttribute() {
		return $this->vendor_bank_account ? $this->vendor_bank_account->bank_branch : '---';
	}

	public function getVendorBankAccountExpiryAttribute() {
		return $this->vendor_bank_account ? $this->vendor_bank_account->expiration_date : '---';
	}

	// Customer Bank

	public function getCustomerBankAccountHolderAttribute() {
		return $this->customer_bank_account ? $this->customer_bank_account->account_holder : '---';
	}

	public function getCustomerBankAccountTypeAttribute() {
		return $this->customer_bank_account ? $this->customer_bank_account->bank_account_type : '---';
	}

	public function getCustomerBankNameAttribute() {
		return $this->customer_bank_account ? $this->customer_bank_account->bank_name : '---';
	}

	public function getCustomerBankBranchAttribute() {
		return $this->customer_bank_account ? $this->customer_bank_account->bank_branch : '---';
	}

	public function getCustomerBankAccountExpiryAttribute() {
		return $this->customer_bank_account ? $this->customer_bank_account->expiration_date : '---';
	}

	public function getApprovedInvoiceCheckboxAttribute() {
		return '';
	}

	// Posting

	public function getPostedInvoiceCheckboxAttribute() {
		return $this->posting_date ? true : false;
	}

	public function getPostingDateAttribute() {
		return $this->voucher ? $this->voucher->posted_on : '';
	}
	
	public function getPostedByAttribute() {
		return isset($this->voucher->posted_by_user) ? $this->voucher->posted_by_user->renderName() : '';
	}

	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderApprovedUser() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('checks.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('checks.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('checks.restore', $this->id);
    }

    public function renderCancelUrl() {
        return route('checks.cancel', $this->id);
    }

    public function renderApproveUrl() {
        return route('checks.approve', $this->id);
    }
}
