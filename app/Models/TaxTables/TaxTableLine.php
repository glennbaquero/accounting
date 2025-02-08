<?php

namespace App\Models\TaxTables;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use Carbon\Carbon;

class TaxTableLine extends Model
{
	protected $casts = [
		'procurement_posting' => 'integer',
	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	    ];
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'tax_id',
		'tax_name',
		'tax_posting_id',
		'tax_posting',
		'description',
		'level',
		'applied_to',
		'tax_percent',
		'peza_checkbox',
		'vat_exempt_number_checkbox',
		'major_industry_clasification',
		'industry_clasification_group',
		'psic_sections',
		'psic_divisions',
		'psic_groups',
		'psic_class',
		'psic_subclass',
		'procurement_posting',
		'product_id',
		'variant_id',
		'service_id',
		'service_task_id',
		'delivery_type',
		'tax_account_code_number',
		'tax_account',
	])
	{
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        ]);
	    } else {
	        $item->update($vars);
	        $item->update([
	        	'updated_by' => $request->user()->id,
	        ]);
	    }

	    return $item;
	}

	/**
	 * Renders
	 */
	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderUpdateUrl() {
        return route('tax-table-lines.update', $this->id);
    }

	public function renderShowUrl() {
        return route('tax-table-lines.fetch-item', $this->id);
    }

    public function renderArchiveUrl() {
        return route('tax-table-lines.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('tax-table-lines.restore', $this->id);
    }

}
