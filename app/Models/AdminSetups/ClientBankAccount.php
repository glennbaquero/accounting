<?php

namespace App\Models\AdminSetups;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\Client;
use App\Models\Journals\InvoiceApprovalJournal;
use App\Models\Journals\CustomerInvoiceJournal;

use Carbon\Carbon;

class ClientBankAccount extends Model
{
	protected $casts = [
		// 'active_date' => 'date',
		// 'expiration_date' => 'date',
	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'client' => $this->client ? $this->client->name : '---',
            'bank_account' => $this->bank_account,
            'name' => $this->name,
            'bank_groups' => $this->bank_groups,
            'bank_account_status' => $this->bank_account_status,
            'bank_account_number' => $this->bank_account_number,
            'account_holder' => $this->account_holder,
            'bank_account_type' => $this->bank_account_type,
            'routing_number' => $this->routing_number,
            'bank_name' => $this->bank_name,
            'bank_branch' => $this->bank_branch,
            'swift_code' => $this->swift_code,
            'iban' => $this->iban,
            'post_fee_checkbox' => $this->post_fee_checkbox,
            'fee_account' => $this->fee_account,
            'clearing' => $this->clearing,
            'cost_center' => $this->cost_center,
            'department' => $this->department,
            'expense_purpose' => $this->expense_purpose,
            'text_code' => $this->text_code,
            'message_to_bank' => $this->message_to_bank,
            'address' => $this->address,
            'name_of_person' => $this->name_of_person,
            'telephone' => $this->telephone,
            'extension' => $this->extension,
            'pager' => $this->pager,
            'mobile_phone' => $this->mobile_phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'sms' => $this->sms,
            'internet_address' => $this->internet_address,
            'telex_number' => $this->telex_number,
            'opening_balance' => $this->opening_balance,
            'remaining_balance' => $this->remaining_balance,
            'client_id' => $this->client_id,
            'created_at' => $this->renderDate(),
            'deleted_at' => $this->deleted_at,
	    ];
	}

	/**
	 * Relationships
	 */
	public function client() {
		return $this->belongsTo(Client::class)->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	public function invoice_approval_journals() {
		return $this->hasMany(InvoiceApprovalJournal::class, 'bank_account', 'id')->withTrashed();
	}

	public function customer_invoice_approval_journals() {
		return $this->hasMany(CustomerInvoiceJournal::class, 'bank_account', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client',
		'bank_groups',
		'active_date',
		'expiration_date',
		'bank_account_number',
		'account_holder',
		'bank_account_type',
		'routing_number',
		'bank_name',
		'bank_branch',
		'swift_code',
		'iban',
		'post_fee_checkbox',
		'fee_account',
		'clearing',
		'cost_center',
		'department',
		'expense_purpose',
		'text_code',
		'message_to_bank',
		'address',
		'name_of_person',
		'telephone',
		'extension',
		'pager',
		'mobile_phone',
		'fax',
		'email',
		'sms',
		'internet_address',
		'telex_number',
		'client_id',
		'posting_profile',
		'accouting_distribution',
		'managed_by',
		'authorized_by',
		'division',
		'opening_balance',
		'remaining_balance',
		'bank_balance',
		'main_account_id',
		'cash_clearing_account',
		'cash_clearing_account_code',
		'not_sufficient_account',
		'not_sufficient_account_code',
		'credit_limit',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $bank_account = 'bank-account-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'bank_account' => $bank_account,
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

	/**
	 * Mutators
	 */
	public function getBankAccountStatusAttribute($value) {
		return $this->renderStatus();
	}


	/**
	 * Renders
	 */
	public function renderStatus($column = 'label') {
		$result = '';
		$active_date = Carbon::parse($this->active_date);
		$expiration_date = Carbon::parse($this->expiration_date);
		$today = Carbon::now();

		if($today >= $active_date && $today <= $expiration_date) {
			switch ($column) {
				case 'class':
					return 'bg-success';
				break;
				default:
					return 'Active';
				break;
			}
		}else {
			switch ($column) {
				case 'class':
					return 'bg-danger';
				break;
				default:
					return 'Inactive';
				break;
			}
		}

		return $result;

	}

	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('client-bank-accounts.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('client-bank-accounts.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('client-bank-accounts.restore', $this->id);
    }
}
