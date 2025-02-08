<?php

namespace App\Models\FinancialDimensions;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Departments\Department;

use App\Models\Users\User;

class FinancialDimension extends Model
{
	/**
	 * Relationships
	 */

    public function vendor() {
		return $this->belongsTo(Department::class, 'department_code', 'department_code')->withTrashed();
	}

    public function financial_dimension_values() {
		return $this->hasMany(FinancialDimensionValue::class, 'financial_dimension', 'financial_dimension');
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
	public static function store($request, $item = null, $columns = ['use_value_from', 'financial_dimension', 'dimension_code','dimension_name', 'report_column_name', 'dimension_value_mask', 'require_values_for_the_dimension_to_be_balanced_with',  'created_by', 'updated_by'])
	{

		$vars = $request->only($columns);
	    $vars['require_balanced_dimension'] = $request->filled('require_balanced_dimension');

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

	public static function renderFinancialDimensionValues($type = '') {
		$result = [];
		$result = static::where('use_value_from', $type)->first();
		if($result) {
			return $result->financial_dimension_values;
		}
		
		return $result;
		
	}
	
	
	public function renderShowUrl() {
        return route('financial-dimensions.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('financial-dimensions.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('financial-dimensions.restore', $this->id);
    }
}
