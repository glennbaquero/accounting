<?php

namespace App\Models\LedgerSetup;

use App\Extenders\Models\BaseModel as Model;

use App\Models\ChartOfAccounts\ChartOfAccount;

use App\Models\Users\User;

class ChartOfAccountMainAccount extends Model
{

    /**
	 * Relationships
	 */
	
    public function parent() {
		return $this->belongsTo(ChartOfAccount::class, 'coa_id', 'coa_id')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}	

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
            'coa_main_account_code' => $this->coa_main_account_code,
            'coa_main_account_name' => $this->coa_main_account_name,
            'main_account_category' => $this->main_account_category,
            'chart_of_accounts_code' => $this->chart_of_accounts_code
	    ];
    }

    
	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['coa_main_account_id', 'coa_main_account_code','coa_main_account_name', 'main_account_type', 'main_account_type', 'coa_id','coa_code','coa_name',  'description', 'coa_status','created_by', 'updated_by',])
	{
	    $vars = $request->only($columns);

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
        return route('chart-of-accounts-main-account.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('chart-of-accounts-main-account.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('chart-of-accounts-main-account.restore', $this->id);
    }

}
