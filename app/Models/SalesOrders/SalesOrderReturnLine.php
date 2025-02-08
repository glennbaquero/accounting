<?php

namespace App\Models\SalesOrders;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Customers\Customer;
use App\Models\Users\User;
use App\Models\JournalSetups\CostCenter;
use App\Models\JournalSetups\Product;

class SalesOrderReturnLine extends Model
{
	protected $casts = [
        'product' => 'object',
		'variant' => 'object',
    ];

        protected $appends = [ 'removeUrl', 'existing_data', 'approveUrl', 'rejectUrl'];

    	/**
    	 * Get the indexable data array for the model.
    	 *
    	 * @return array
    	 */
    	public function toSearchableArray() {
    	    return [
    			'id' => $this->id,
    	        'sales_order_return_line_number' => $this->sales_order_return_line_number,
    	        'line_number' => $this->line_number,
    	        'line_status' => $this->line_status,
    	    ];
    	}


    	/**
    	 * Relationships
    	 */
    	
    	public function sales_order_return() {
    		return $this->belongsTo(SalesOrderReturn::class, 'sales_order_return_number', 'sales_order_return_number')->withTrashed();
    	}

        public function customer() {
    		return $this->belongsTo(Customer::class, 'customer_account', 'customer_account')->withTrashed();
    	}

    	public function created_by_user() {
    		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
    	}

    	public function updated_by_user() {
    		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
    	}

        public function product() {
    		return $this->belongsTo(Product::class, 'item_number', 'item_number')->withTrashed();
    	}

    	public function cost_center() {
            return $this->belongsTo(FinancialDimensionValue::class, 'cost_center_id', 'id')->withTrashed();
        }

        public function department() {
            return $this->belongsTo(FinancialDimensionValue::class, 'department_id', 'id')->withTrashed();
        }
        
        public function expense_purpose() {
            return $this->belongsTo(FinancialDimensionValue::class, 'expense_purpose_id', 'id')->withTrashed();
        }

    	/**
    	 * Renderers
    	 */
    	
    	public function removeUrl() {
    		return route('sales-order-return-lines.archive', $this->id);
    	}

    	public function renderApproveUrl() {
    		return route('sales-order-return-lines.approve', $this->id);
    	}

    	public function renderRejectUrl() {
    		return route('sales-order-return-lines.reject', $this->id);
    	}

    	/**
    	 * Appends
    	 */
    	
    	public function getRemoveUrlAttribute() {
    		return $this->removeUrl();
    	}

    	public function getApproveUrlAttribute() {
    		return $this->renderApproveUrl();
    	}

    	public function getRejectUrlAttribute() {
    		return $this->renderRejectUrl();
    	}

    	public function getExistingDataAttribute() {
    		return true;
    	}
}
