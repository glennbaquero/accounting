<?php

namespace App\Models\LinkedMainAccounts;

use App\Extenders\Models\BaseModel as Model;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;
use App\Models\AdminSetups\Client;

class LinkedMainAccount extends Model
{
	/**
	 * @Relationship
	 */


	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}

	public function client() {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	public function main_accounts() {
		return $this->belongsToMany(MainAccount::class, 'linked_main_accounts_main_accounts', 'linked_main_account_id', 'main_account_id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['linked_main_account_code', 'chart_of_accounts_code', 'chart_of_accounts_name', 'main_account_code', 'main_account', 'main_account_type', 'main_account_category', 'description', 'linked_checkbox', 'created_by', 'updated_by', 'client_id'])
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
	
	/**
	 * Renderers
	 */
		
	public function renderShowUrl() {
        return route('linked-main-accounts.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('linked-main-accounts.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('linked-main-accounts.restore', $this->id);
    }
}
