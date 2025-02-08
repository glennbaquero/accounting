<?php

namespace App\Models\BankAccountTransactions;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\CustomerBankAccount;
use App\Models\Vendors\VendorBankAccount;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\AdminSetups\Client;

use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\BankAccountStatements\BankAccountStatement;

use Carbon\Carbon;

class BankAccountTransaction extends Model
{
	/**
	 * @Relationship
	 */
	public function statements() {
		return $this->hasMany(BankAccountStatement::class, 'bank_account_transaction_number', 'bank_account_transaction_number');
	}

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
			'vendor_bank_account_number' => $this->vendor_bank_account_number,
			'method_of_payment_customer' => $this->method_of_payment_customer,
			'method_of_payment_vendor' => $this->method_of_payment_vendor,
			'vendor_company' => $this->vendor_company,
			'vendor_contact' => $this->vendor_contact,
			'bank_statement' => $this->bank_statement,
			'bank_statement_date' => $this->bank_statement_date,
			'transaction_date' => $this->transaction_date,
			'issued_by' => $this->issued_by,
			'bank_posting_profile' => $this->bank_posting_profile,
			'deposit_slip_number' => $this->deposit_slip_number,
			'check_number' => $this->check_number,
			'cleared_checkbox' => $this->cleared_checkbox,
			'reconciled_checkbox' => $this->reconciled_checkbox,
			'manual_checkbox' => $this->manual_checkbox,
			'pending_cancellation_checkbox' => $this->pending_cancellation_checkbox,
			'reason_code' => $this->reason_code,
			'reason_comment' => $this->reason_comment,
			'voucher_number' => $this->voucher_number,
			'accounting_date' => $this->accounting_date,
			'cost_center' => $this->cost_center,
			'department' => $this->department,
			'expense_purpose' => $this->expense_purpose,
	    ];
	}

	/**
	 * Relationships
	 */
	public function vendor_payment_method() {
		return $this->belongsTo(VendorPaymentMethod::class, 'method_of_payment_vendor', 'method_of_payment_id')->withTrashed();
	}

	public function customer_payment_method() {
		return $this->belongsTo(CustomerPaymentMethod::class, 'method_of_payment_customer', 'method_of_payment_id')->withTrashed();
	}

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

	public function voucher() {
		return $this->belongsTo(VendorPaymentJournalVoucher::class, 'voucher_number', 'voucher_number')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'client_bank_account_number',
		'customer_bank_account_number',
		'vendor_bank_account_number',
		'method_of_payment_customer',
		'method_of_payment_vendor',
		'vendor_company',
		'vendor_contact',
		'bank_statement',
		'bank_statement_date',
		'transaction_date',
		'issued_by',
		'bank_posting_profile',
		'deposit_slip_number',
		'check_number',
		'cleared_checkbox',
		'reconciled_checkbox',
		'manual_checkbox',
		'pending_cancellation_checkbox',
		'reason_code',
		'reason_comment',
		'voucher_number',
		'accounting_date',
		'cost_center',
		'department',
		'expense_purpose',
	])
	{

		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $bank_account_transaction_number = 'BA-transaction-number-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'bank_account_transaction_number' => $bank_account_transaction_number,
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
        return route('bank-account-transactions.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-account-transactions.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-account-transactions.restore', $this->id);
    }

}
