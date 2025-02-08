<?php

namespace App\Models\PostingProfile;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class VendorPostingProfileHeader extends Model
{

	const VENDOR_INVOICE = 'vendor-invoice';
	const PURCHASE_ORDER_RETURNS = 'purchase-order-returns';
	const VENDOR_PAYMENT = 'vendor-payment';

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
        return $this->hasMany(VendorPostingProfile::class, 'posting_header_id');
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
			['name' => 'Vendor Invoice', 'value' => static::VENDOR_INVOICE],
			['name' => 'Vendor Payment', 'value' => static::VENDOR_PAYMENT],
			['name' => 'Purchase Order Return', 'value' => static::PURCHASE_ORDER_RETURNS],
		];
	}

}
