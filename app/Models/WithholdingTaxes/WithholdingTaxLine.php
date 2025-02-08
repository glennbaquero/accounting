<?php

namespace App\Models\WithholdingTaxes;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class WithholdingTaxLine extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'client_id' => $this->client_id,
			'withholding_tax_id' => $this->withholding_tax_id,
			'withholding_tax_name' => $this->withholding_tax_name,
			'withholding_tax_posting_id' => $this->withholding_tax_posting_id,
			'withholding_tax_posting' => $this->withholding_tax_posting,
			'description' => $this->description,
			'minimum_amount' => $this->minimum_amount,
			'maximum_amount' => $this->maximum_amount,
			'tax_percent' => $this->tax_percent,
			'withholding_tax_exemptions_checkbox' => $this->withholding_tax_exemptions_checkbox,
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
		'withholding_tax_name',
		'withholding_tax_posting_id',
		'withholding_tax_posting',
		'description',
		'minimum_amount',
		'maximum_amount',
		'tax_percent',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;
		$vars['withholding_tax_exemptions_checkbox'] = $request->filled('withholding_tax_exemptions_checkbox');

	    if (!$item) {
	        $item = static::create($vars);
	        $withholding_tax_id = 'withholding-tax-id-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
				'withholding_tax_id' => $withholding_tax_id,
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
        return route('withholding-tax-lines.update', $this->id);
    }

	public function renderShowUrl() {
        return route('withholding-tax-lines.fetch-item', $this->id);
    }

    public function renderArchiveUrl() {
        return route('withholding-tax-lines.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('withholding-tax-lines.restore', $this->id);
    }
}
