<?php

namespace App\Models\FinancialDimensions;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;

class FinancialDimensionValue extends Model
{
	/**
	 * @Relationship
	 */

    public function parent() {
		return $this->belongsTo(FinancialDimension::class, 'financial_dimension', 'financial_dimension')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['financial_dimension_value_code', 'financial_dimension', 'dimension_name', 'dimension_value_name','description', 'select_the_level_of_dimension_value_to_display', 'companies', 'active_from', 'active_to', 'owner', 'group_dimension', 'do_not_allow_manual_entry', 'invert_sign', 'created_by', 'updated_by'])
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
