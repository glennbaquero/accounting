<?php

namespace App\Models\WithholdingTaxes;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class WithholdingTax extends Model
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
			'withholding_tax_posting' => $this->withholding_tax_posting,
			'withholding_tax_posting_name' => $this->withholding_tax_posting_name,
			'description' => $this->description,
			'effective_date' => $this->effective_date,
			'expiration_date' => $this->expiration_date,
			'withholding_tax_percent' => $this->withholding_tax_percent,
			'withholding_tax_exemptions_checkbox' => $this->withholding_tax_exemptions_checkbox,
			'withholding_tax_debit_account' => $this->withholding_tax_debit_account,
			'withholding_tax_debit_account_code_number' => $this->withholding_tax_debit_account_code_number,
			'withholding_tax_credit_account' => $this->withholding_tax_credit_account,
			'withholding_tax_credit_account_code_number' => $this->withholding_tax_credit_account_code_number,
			'withholding_tax_debit_offset_account' => $this->withholding_tax_debit_offset_account,
			'withholding_tax_debit_offset_account_code_number' => $this->withholding_tax_debit_offset_account_code_number,
			'withholding_tax_credit_offset_account' => $this->withholding_tax_credit_offset_account,
			'withholding_tax_credit_offset_account_code_number' => $this->withholding_tax_credit_offset_account_code_number,
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
		'withholding_tax_posting',
		'withholding_tax_posting_name',
		'description',
		'effective_date',
		'expiration_date',
		'withholding_tax_percent',
		'withholding_tax_debit_account',
		'withholding_tax_debit_account_code_number',
		'withholding_tax_credit_account',
		'withholding_tax_credit_account_code_number',
		'withholding_tax_debit_offset_account',
		'withholding_tax_debit_offset_account_code_number',
		'withholding_tax_credit_offset_account',
		'withholding_tax_credit_offset_account_code_number',
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
        return route('withholding-taxes.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('withholding-taxes.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('withholding-taxes.restore', $this->id);
    }
}
