<?php

namespace App\Models\Deposits;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\Customer;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\AdminSetups\Client;

use Carbon\Carbon;

class Deposit extends Model
{
	protected $appends = [
		'client_bank_account_holder',
		'client_bank_account_type',
		'client_bank_name',
		'client_bank_branch',
		'client_bank_account_expiry',

		'posted_invoice_checkbox',
		'posting_date',
		'posted_by',

		'customer_company',
		'customer_contact',
	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'client_bank_account_number' => $this->client_bank_account_number,
			'customer_company' => $this->customer_company,
			'customer_contact' => $this->customer_contact,
			'deposit_slip_id' => $this->deposit_slip_id,
			'deposit_slip_number' => $this->deposit_slip_number,
			'issue_date' => $this->issue_date,
			'bank_posting_profile' => $this->bank_posting_profile,
			'method_of_payment_customer' => $this->method_of_payment_customer,
			'payment_reference' => $this->payment_reference,
			'canceled' => $this->canceled,
			'pending_cancellation' => $this->pending_cancellation,
			'reason_code' => $this->reason_code,
			'reason_comment' => $this->reason_comment,
			'description' => $this->description,
			'approved_date' => $this->approved_date,
			'approved_by' => $this->approved_by,
			'cost_center' => $this->cost_center,
			'department' => $this->department,
			'expense_purpose' => $this->expense_purpose,
			'created_by' => $this->created_by,
			'updated_by' => $this->updated_by,
			'voucher_no' => $this->voucher_no,
			'vendor_account' => $this->vendor_account,
			'vendor_bank_account' => $this->vendor_bank_account,
			'bank_account_number' => $this->bank_account_number,
	    ];
	}

	/**
	 * Relationships
	 */
	public function client() {
		return $this->belongsTo(Client::class)->withTrashed();
	}

	public function client_bank_account() {
		return $this->belongsTo(ClientBankAccount::class, 'client_bank_account_number', 'bank_account')->withTrashed();
	}

	public function customer() {
		return $this->belongsTo(Customer::class, 'customer_number', 'customer_number')->withTrashed();
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

	public function canceled_by_user() {
		return $this->belongsTo(User::class, 'canceled_by', 'id')->withTrashed();
	}

	public function customer_account() {
		return $this->belongsTo(Customer::class, 'customer_account', 'customer_account')->withTrashed();
	}

	public function voucher() {
		return $this->belongsTo(VendorPaymentJournalVoucher::class, 'voucher_no', 'voucher_number')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'client_bank_account_number',
		'customer_company',
		'customer_contact',
		'deposit_slip_number',
		'issue_date',
		'bank_posting_profile',
		'method_of_payment_customer',
		'payment_reference',
		'pending_cancellation',
		'reason_code',
		'reason_comment',
		'description',
		'cost_center',
		'department',
		'expense_purpose',
		'customer_account',
		'deposit_amount',
		'voucher_no',
		// 'canceled',
		// 'approved_date',
		// 'approved_by',
		// 'voucher_no',
		// 'vendor_account',
		// 'vendor_bank_account',
		// 'bank_account_number',

		'client_bank_account_number',
		'customer_bank_account_number',
		'vendor_bank_account_number',

		'method_of_payment_customer',
		'method_of_payment_vendor',
		
		'vendor_payment_status',
		'customer_payment_status',

		'customer_payment_id',
		'vendor_payment_id',

		'vendor_contact',
		'vendor_company',
	])
	{

		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $deposit_slip_id = 'deposit-slip-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'deposit_slip_id' => $deposit_slip_id,
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        ]);
	    } else {
	        $item->update($vars);
	        $item->update([
	        	'updated_by' => $request->user()->id,
	        ]);
	    }

	    return $item;
	}

	public function markCanceled($request) {
		if($this->approved_date) {
			return false;
		}

		return $this->update([
			'canceled' => true,
			'canceled_date' => now(),
			'canceled_by' => $request->user()->id,
		]);
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

	public function getCustomerCompanyAttribute() {
		return $this->customer ? $this->customer->company : '';
	}

	public function getCustomerContactAttribute() {
		return $this->customer ? $this->customer->mobile_number : '';
	}

	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderApprovedBy() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}

	public function renderCanceledBy() {
		return $this->canceled_by_user ? $this->canceled_by_user->renderName() : ''; 
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

	public function renderShowUrl() {
        return route('deposits.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('deposits.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('deposits.restore', $this->id);
    }

    public function renderCancelUrl() {
        return route('deposits.cancel', $this->id);
    }

    public function renderApproveUrl() {
        return route('deposits.approve', $this->id);
    }
}
