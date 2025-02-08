<?php

namespace App\Models\PostingProfile;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

class TransactionPosting extends Model
{

	public static $VENDOR_INVOICE = 'vendor-invoice';
	public static $PURCHASE_ORDER_RETURNS = 'purchase-order-returns';
	public static $VENDOR_PAYMENT = 'vendor-payment';

	protected $casts = [
       'group_number' => 'array',
	   'offset_account' => 'integer',
	   'main_account' => 'integer',
	   'procurement_posting' => 'integer',
	   'method_of_payment_vendor' => 'integer',
	   'method_of_payment_customer' => 'integer',
	   'bank_posting' => 'integer',
	   'match_account' => 'integer',
	   'link_account' => 'integer',
   	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'posting_profile' => $this->posting_profile,
	        'description' => $this->description,
	        'account_code' => $this->account_code,
	        'account' => $this->account,
	        'group_number' => $this->group_number,
	        'summary_account' => $this->summary_account,
	        'settle_account' => $this->settle_account,
	        'sales_tax_prepayments' => $this->sales_tax_prepayments,
	        'arrival' => $this->arrival,
	        'offset_account' => $this->offset_account,
	    ];
	}


	/**
	 * @Relationship
	 */

	public function client()
	{
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by');
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'main_account_type',
		'match_account',
		'match_account_number',
		'main_account',
		'main_account_number',
		'priority',
		'journal',
		'document_attribute',
		'document_values',
		'method_of_payment_vendor',
		'procurement_posting',
		'method_of_payment_vendor',
		'method_of_payment_customer',
		'settlement_type',
		'bank_posting',
		'type_of_account',
		'debit_account_description',
		'credit_account_description',
		'main_account', 
		'use_procurement_account', 
		'posting_header_id', 
		'posting_profile', 
		'description', 
		'account_code', 
		'account', 
		'group_number', 
		'summary_account', 
		'settle_account', 
		'sales_tax_prepayments', 
		'arrival', 
		'offset_account', 
		'client_id', 
		'summary_account_code', 
		'journal_name', 
		'offset_account_code', 
		'offset_account_type', 
		'settle_account_code', 
		'document', 
		'document_status',
		'link_account_number', 
		'link_account', 
		])
	{
	    $vars = $request->only($columns);
		$auth = auth()->user();
		$vars['company_id'] = auth()->user()->company_id;
		$vars['status'] = $request->filled('status') ? true : false;
	
	    if (!$item) {
            $vars['created_by'] = $auth->id;
			$vars['created_on'] = now();
	        $item = static::create($vars);
	    } else {
            $vars['updated_by'] = $auth->id;
            $vars['updated_on'] = now();
	        $item->update($vars);
	    }

	    return $item;
	}

	/**
	 * Relationships
	 */
	public function summaryAccount()
	{
		return $this->belongsTo(MainAccount::class, 'summary_account', 'id')->withTrashed();
	}

	public function settle_account_details()
	{
		return $this->belongsTo(MainAccount::class, 'settle_account', 'id')->withTrashed();
	}

	public function offset_account_details()
	{
		return $this->belongsTo(MainAccount::class, 'offset_account', 'id')->withTrashed();
	}


	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('transaction-postings.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('transaction-postings.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('transaction-postings.restore', $this->id);
    }

	public static function renderTypesOfAccount() {
		return [
			'Real',
			'Personal',
			'Nominal',
		];
	}

	public static function renderDebitAccount() {
		return [
			'Debit what comes in',
			'Debit the receiver',
			'Debit all expenses and losses',
		];
	}

	public static function renderCreditAccount() {
		return [
			'Credit what goes out',
			'Credit the giver',
			'Credit all incomes and gains',
		];
	}

	public static function renderSettlementTypes() {
		return [
			'None',
			'Open Transaction',
			'Designated Transactions'
		];
	}

	public static function renderJournals() {
		return [
			'Invoice Approval Journal',
			'Vendor Payment Journal',
			'Promisory Note Journal',
			'Purchase Order Journal',
			'Customer Invoice Journal',
			'Customer Payment Journal',
			'Sales Order Return Journal',
		];
	}

	public static function renderMainAccountTypes() {
		return [
			'Ledger',
			'Customer',
			'Vendor',
			'Project',
			'Fixed Assets',
			'Bank',
		];
	}

}
