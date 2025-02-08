<?php

namespace App\Models\LedgerSetups\DocumentCodeControls;

use App\Extenders\Models\BaseModel;
use App\Models\AdminSetups\Client;
use Carbon\Carbon;

class DocumentCodeControl extends BaseModel
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'module' => $this->name,
	    ];
	}

	/**
	* Relationships
	*/

	public function client() {
		return $this->belongsTo(Client::class, 'client_id');
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['module_id', 'company_id', 'client_id', 'column_1', 'column_2', 'column_1_type', 'column_2_type', 'separated_by', 'prefix'])
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
        return route('document-code-controls.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('document-code-controls.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('document-code-controls.restore', $this->id);
    }

	public function renderName() {
		$lookup = array_column(static::getModules(), NULL, 'id');
		$module = $lookup[$this->module_id];
		return $module['name'];
	}

	/**
	 * Getters
	 */

	public static function generateCode($client, $type, $model) {

		$dc = DocumentCodeControl::where('client_id', $client)->where('module_id', $type)->where('active', true)->first();
		if($dc) {
			$count = $model::whereMonth('created_at', Carbon::now()->month)->withTrashed()->count() + 1;
			$code = $dc->prefix . $dc->separated_by;

			switch ($dc->column_1_type) {
				case 1:
					$code .= $dc->column_1 . $dc->separated_by;
					break;
				case 2:
					$code .= str_pad($count, 5, '0', STR_PAD_LEFT) . $dc->separated_by;
					break;
				case 3:
					$code .= Carbon::now()->format('mY') . $dc->separated_by;
					break;
			}

			switch ($dc->column_2_type) {
				case 1:
					$code .= $dc->column_2;
					break;
				case 2:
					$code .= str_pad($count, 5, '0', STR_PAD_LEFT);
					break;
				case 3:
					$code .= Carbon::now()->format('mY');
					break;
			}

			return $code;
		}

		return null;
	}

	public function getCode() {
		return $this->prefix . $this->separated_by . $this->column_1 . $this->separated_by . $this->column_2;
	}

	public static function getType($type) {
		switch ($type) {
			case 1:
				return 'Text';
				break;
			case 2:
				return 'Auto Increment';
				break;
			case 3:
				return 'Date (mm-yyyy)';
				break;
			default:
				return 'Type not found!';
				break;
		}
	}
	public static function getModules() {
		return [
			['id' => 1, 'name' => 'Purchase Order'],
			['id' => 2, 'name' => 'Vendor Invoice'],
			['id' => 3, 'name' => 'Vendor Payment'],
			['id' => 4, 'name' => 'Sales Order'],
			['id' => 5, 'name' => 'Customer Invoice'],
			['id' => 6, 'name' => 'Customer Payment'],
		];
	}

}
