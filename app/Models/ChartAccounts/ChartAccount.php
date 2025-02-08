<?php

namespace App\Models\ChartAccounts;

use App\Extenders\Models\BaseModel as Model;

class ChartAccount extends Model
{
	/**
	 *  Relationships
	 */

 //    public function vendor() {
	// 	return $this->belongsTo(Department::class, 'department_code', 'department_code')->withTrashed();
	// }

    public function chart_account_values() {
		return $this->hasMany(ChartAccountValue::class, 'chart_of_accounts_id', 'chart_of_accounts_id');
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['chart_of_accounts_id','chart_of_accounts_code', 'chart_of_accounts_name', 'main_account_mask', 'description',])
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
        return route('chart-of-accounts.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('chart-of-accounts.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('chart-of-accounts.restore', $this->id);
    }
}
