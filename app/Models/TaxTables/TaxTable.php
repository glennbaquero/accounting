<?php

namespace App\Models\TaxTables;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use Carbon\Carbon;

class TaxTable extends Model
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
			'tax_posting' => $this->tax_posting,
			'tax_posting_name' => $this->tax_posting_name,
			'description' => $this->description,
			'tax_percent' => $this->tax_percent,
			'peza_checkbox' => $this->peza_checkbox,
			'vat_exempt_number_checkbox' => $this->vat_exempt_number_checkbox,
			'tax_account_code_number' => $this->tax_account_code_number,
			'tax_account' => $this->tax_account,
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
		'tax_posting',
		'tax_posting_name',
		'description',
		'tax_percent',
		'peza_checkbox',
		'vat_exempt_number_checkbox',
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

	public function renderShowUrl() {
        return route('tax-tables.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('tax-tables.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('tax-tables.restore', $this->id);
    }
}
