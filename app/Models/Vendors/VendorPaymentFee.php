<?php

namespace App\Models\Vendors;

use App\Extenders\Models\BaseModel as Model;

use App\Models\MainAccounts\MainAccount;
use DB;

class VendorPaymentFee extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
            'id' => $this->id,
            'fee_id' => $this->fee_id,
	        'name' => $this->name,
	        'charge_to' => $this->charge_to,
	        'fee_account_code' => $this->fee_account_code,
	        'fee_account' => $this->fee_account,
	    ];
    }

    public function mainAccount() 
    {
    	return $this->belongsTo(MainAccount::class, 'fee_account', 'id')->withTrashed();
    }
    
    
    public static function store($request, $item = null, $columns = [
		'fee_id',
		'name',
		'description',
		'client_id',
		'charge_to',
		'fee_account',
	]) {
		DB::beginTransaction();

        $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

        if (! $item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        DB::commit();
        
        return $item;
    }


    public function renderShowUrl() {
        return route('vendor-payment-fees.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('vendor-payment-fees.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('vendor-payment-fees.restore', $this->id);
    }
}
