<?php

namespace App\Models\MainAccounts;

use App\Extenders\Models\BaseModel as Model;

use App\Models\MainAccountCategories\MainAccountCategory;

use App\Models\LedgerSetup\ChartOfAccount;

use App\Models\Users\User;

use App\Models\AdminSetups\Client;

class MainAccount extends Model
{
	/**
	 * @Relationship
	 */

 	public function main_account_category_selected() {
	 	return $this->belongsTo(MainAccountCategory::class, 'main_account_category_id')->withTrashed();
	}

 	public function main_account_coa_selected() {
	 	return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id')->withTrashed();
	}	

	public function created_by_user() {	
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}

	public function client() {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	public function clients() {
        return $this->belongsToMany(Client::class, 'users_clients', 'user_id', 'client_id');
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [ 'debit_credit_increase_rule' ,'debit_credit_decrease_rule' ,'main_account_id', 'main_account_code_number','main_account_code', 'main_account_name', 'chart_of_account_id', 'coa_code', 'coa_name', 'description', 'main_account_type', 'reporting_type', 'main_account_category_id', 'db_cr_requirement', 'db_cr_proposal', 
		'balance_control', 'offset_account', 'opening_account', 'active_from', 'active_to', 'active_to', 'close', 'default_consolidation_account', 'posting_type', 'validate_posting', 'sales_tax_group', 'item_sales_tax_group', 'sales_tax_direction', 'exempt', 'sales_tax_code', 'validate_sales_tax', 'created_by', 'updated_by', 'client_id', 'not_sufficient_account'])
	{
		$vars = $request->only($columns);
	    $vars['do_not_allow_manual_entry'] = $request->filled('do_not_allow_manual_entry');
	    $vars['is_shared'] = $request->filled('is_shared');
		$vars['suspended'] = $request->filled('suspended');	
		$vars['monetary'] = $request->filled('monetary');		    
		$vars['exempt'] = $request->filled('exempt');		    
		$vars['invert_sign'] = $request->filled('invert_sign');		    
		$vars['column'] = $request->filled('column');		    
		$vars['bold'] = $request->filled('bold');		    
		$vars['italics'] = $request->filled('italics');		  
		$vars['line_above'] = $request->filled('line_above');		  
		$vars['line_below'] = $request->filled('line_below');		  
		$vars['underline_text'] = $request->filled('underline_text');		  
		$vars['underline_amount'] = $request->filled('underline_amount');		
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
        return route('main-accounts.show', $this->id);
    }

	public function renderShowCOAUrl() {
        return route('main-accounts.show-coa', $this->id);
    }    

    public function renderArchiveUrl() {
        return route('main-accounts.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('main-accounts.restore', $this->id);
    }

	public function renderAttachLinkMainAccountUrl() {
        return route('main-accounts.attach-linked-main-accounts', $this->id);
    }

	public function renderDetachLinkMainAccountUrl() {
        return route('main-accounts.detach-linked-main-accounts', $this->id);
    }
}
