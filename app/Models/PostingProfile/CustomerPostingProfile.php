<?php

namespace App\Models\PostingProfile;

use App\Extenders\Models\BaseModel as Model;

use App\Models\MainAccounts\MainAccount;

class CustomerPostingProfile extends Model
{
    protected $casts = [
       'group_number' => 'array',
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
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['posting_profile', 'description', 'account_code', 'account', 'group_number', 'summary_account', 'settle_account', 'sales_tax_prepayments', 'arrival', 'offset_account', 'client_id', 'summary_account_code', 'journal_name', 'offset_account_code', 'offset_account_type', 'settle_account_code', 'document', 'document_status', 'posting_header_id'])
	{
		
	    $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);
	    }

	    return $item;
	}

	public function summaryAccount()
	{
		return $this->belongsTo(MainAccount::class, 'summary_account', 'id')->withTrashed();
	}


	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('customer-posting-profiles.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('customer-posting-profiles.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('customer-posting-profiles.restore', $this->id);
    }
}
