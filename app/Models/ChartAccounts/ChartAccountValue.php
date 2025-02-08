<?php

namespace App\Models\ChartAccounts;

use App\Extenders\Models\BaseModel as Model;

class ChartAccountValue extends Model
{
	/**
	 * @Relationship
	 */

    public function parent() {
		return $this->belongsTo(ChartAccount::class, 'chart_of_accounts_id', 'chart_of_accounts_id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['financial_dimension_value_code', 'chart_of_accounts_id', 'dimension_name', 'dimension_value', 'description', 'select_the_level_of_dimension_value_to_display', 'companies', 'active_from', 'active_to', 'owner', 'group_dimension', 'do_not_allow_manual_entry', 'invert_sign',])
	{
		$vars = $request->only($columns);
	    $vars['suspended_checkbox'] = $request->filled('suspended_checkbox');
	    $vars['calculate_total_from_multiple_dimension_values'] = $request->filled('calculate_total_from_multiple_dimension_values');

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
        return route('financial-dimension-values.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('financial-dimension-values.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('financial-dimension-values.restore', $this->id);
    }
}
