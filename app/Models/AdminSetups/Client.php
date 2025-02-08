<?php

namespace App\Models\AdminSetups;

use App\Extenders\Models\BaseModel;
use App\Models\Vendors\VendorBankAccount;
use App\Models\Ledgers\Ledger;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;

class Client extends BaseModel
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'name' => $this->name,
	    ];
	}

	/**
	* Relationships
	*/
    public function ledgers() {
		return $this->hasMany(Ledger::class, 'client_id' , 'id')->withTrashed();
	}

	public function document_codes(){
		return $this->hasMany(DocumentCodeControl::class, 'client_id')->withTrashed();
	}

	public function vendor_bank_accounts() {
		return $this->hasMany(VendorBankAccount::class);
	}


	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['name'])
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
        return route('clients.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('clients.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('clients.restore', $this->id);
    }

	public function renderAttachUserUrl() {
        return route('clients.attach-user', $this->id);
    }

	public function renderDetachUser() {
        return route('clients.detach-user', $this->id);
    }

	/**
	 * Getters
	 */

	 public function getActiveLedger() {
		 return $this->ledgers->where('ledger_status', true)->first();
	 }
	 

}
