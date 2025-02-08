<?php

namespace App\Models\LedgerSetup;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;

use App\Models\Users\User;

class ChartOfAccount extends Model
{

	/**
	 * @Relationship
	 */

 	public function main_accounts() {
	 	return $this->hasMany(MainAccount::class)->withTrashed();
	}

 	public function ledgers() {
	 	return $this->belongsTo(Ledger::class)->withTrashed();
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
            'coa_id' => $this->coa_id,
            'coa_code' => $this->coa_code,
            'coa_name' => $this->coa_name,
            'main_account_mask' => $this->main_account_mask
	    ];
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [ 'coa_id', 'coa_code', 'coa_name','main_account_type','main_account_category','main_account_mask', 'description', 'client_id', 'created_by', 'updated_by'])
	{
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;
		$vars['main_account_mask'] = '---';	

	    if (!$item) {
	        $item = static::create($vars);
	    } else {
			$vars['updated_by'] = auth()->user()->id;
	        $item->update($vars);
	    }

	    return $item;
    }
    
    /**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('chart-of-accounts.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('chart-of-accounts.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('chart-of-accounts.restore', $this->id);
    }

}