<?php

namespace App\Models\MainAccountCategories;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class MainAccountCategory extends Model
{
	/**
	 * @Relationship
	 */

 	public function main_accounts() {
	 	return $this->hasMany(MainAccount::class)->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}

	public function client() {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'main_account_category' => $this->main_account_category,
        ];
    }	

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['main_account_category_reference', 'client_id','main_account_category', 'description', 'main_account_type', 'closed_checkbox', 'created_by', 'updated_by'])
	{
		$vars = $request->only($columns);
	    $vars['closed_checkbox'] = $request->filled('closed_checkbox');
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
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
        return route('main-accounts-categories.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('main-accounts-categories.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('main-accounts-categories.restore', $this->id);
    }
}
