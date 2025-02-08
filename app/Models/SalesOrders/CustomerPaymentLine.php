<?php

namespace App\Models\SalesOrders;

use App\Models\Users\User;
use App\Models\Customers\Customer;
use App\Models\Invoices\CustomerInvoice;
use App\Models\ProductInventories\Products\Product;
use App\Models\Discounts\Discount;
use App\Models\Charges\Charge;

use App\Extenders\Models\BaseModel as Model;

class CustomerPaymentLine extends Model
{
	protected $casts = [
        'item' => 'object',
		'variant' => 'object',
    ];

    protected $appends = [
        'less_discount',
        'add_charge',
    ];

    // TODO: use this in the future
    //       for now user must save after approving a line item
    //       this way we minimize complexity of our app
    // protected $appends = [ 'removeUrl', 'approveUrl', 'rejectUrl'];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
            'id' => $this->id,
            'payment_line_number' => $this->payment_line_number,
            'size' => $this->size,
            'color' => $this->color,
            'quantity' => $this->quantity,
            'procurement_category' => $this->procurement_category,
            'created_at' => $this->created_at,
            'posting_date' => $this->posting_date
	    ];
    }

    public function customer_payment() {
        return $this->belongsTo(CustomerPayment::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function customer_invoice() {
        return $this->belongsTo(CustomerInvoice::class);
    }

    public function cost_center() {
        return $this->belongsTo(FinancialDimensionValue::class, 'dimension_value_cost_center_id', 'id')->withTrashed();
    }

    public function department() {
        return $this->belongsTo(FinancialDimensionValue::class, 'dimension_value_department_id', 'id')->withTrashed();
    }
    
    public function expense_purpose() {
        return $this->belongsTo(FinancialDimensionValue::class, 'dimension_value_expense_purpose_id', 'id')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
    }
    
    /**
	 * Renderers
	 */
	
	public function removeUrl() {
		return route('customer-payment-lines.archive', $this->id);
	}

	public function renderApproveUrl() {
		return route('customer-payment-lines.approve', $this->id);
	}

	public function renderRejectUrl() {
		return route('customer-payment-lines.reject', $this->id);
    }

    public function renderLessDiscount() {
        $result = 0;
        $product = Product::withTrashed()->find($this->product_id);

        if($product) {
            $discount = Discount::where('product_id', $product->id)->first();
            $result = $discount ? $discount->discount_value : $result;
        }

        return $result;
    }

    public function renderAddCharge() {
        $result = 0;
        $product = Product::withTrashed()->find($this->product_id);

        if($product) {
            $charge = Charge::where('product_id', $product->id)->first();
            $result = $charge ? $charge->charge_value : $result;
        }

        return $result;
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

    public function getLessDiscountAttribute($value)
    {   
        return $this->renderLessDiscount();
    }

    public function getAddChargeAttribute($value)
    {   
        return $this->renderAddCharge();
    }
}