<?php

namespace App\Models\PurchaseOrders;

use App\Extenders\Models\BaseModel as Model;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Invoices\VendorInvoice;
use App\Models\Users\User;
use App\Models\Vendors\Vendor;
use App\Models\ProductInventories\Products\Product;
use App\Models\Discounts\Discount;
use App\Models\Charges\Charge;

class VendorPaymentLine extends Model
{
	protected $casts = [
        'product' => 'object',
		'variant' => 'object',
    ];

    protected $appends = [
        'less_discount',
        'add_charge',
    ];

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

    public function vendor_payment() {
        return $this->belongsTo(VendorPayment::class);
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }

    public function vendor_invoice() {
        return $this->belongsTo(VendorInvoice::class);
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
     * @Renders
     */
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
     * @Getters
     */
    public function getLessDiscountAttribute($value)
    {   
        return $this->renderLessDiscount();
    }

    public function getAddChargeAttribute($value)
    {   
        return $this->renderAddCharge();
    }
}
