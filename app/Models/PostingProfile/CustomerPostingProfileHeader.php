<?php

namespace App\Models\PostingProfile;

use Illuminate\Database\Eloquent\Model;

class CustomerPostingProfileHeader extends Model
{
    
	const CUSTOMER_INVOICE = 'customer-invoice';
	const SALES_ORDER_RETURNS = 'sales-order-returns';
	const CUSTOMER_PAYMENT = 'customer-payment';

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'client' => $this->client->name,
            'posting_profile' => $this->posting_profile,
            'descriptions' => $this->descriptions,
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

	public function posting_lines() {
        return $this->hasMany(CustomerPostingProfile::class, 'posting_header_id');
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['posting_profile', 'description', 'client_id', 'document'])
	{
	    $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;


		$auth = auth()->user();
		
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
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('vendor-posting-profile-headers.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('vendor-posting-profile-headers.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('vendor-posting-profile-headers.restore', $this->id);
    }

	public function renderDocument() {
		$lookup = array_column(static::getDocuments(), NULL, 'value');
		return $lookup[$this->document]['name'];
	}

	/**
	 * Getters
	 */
	public static function getDocuments() {
		return [
			['name' => 'Customer Invoice', 'value' => static::CUSTOMER_INVOICE],
			['name' => 'Customer Payment', 'value' => static::CUSTOMER_PAYMENT],
			['name' => 'Sales Order Return', 'value' => static::SALES_ORDER_RETURNS],
		];
	}
}
