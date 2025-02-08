<?php

namespace App\Models\Invoices;

use Carbon\Carbon;

use App\Models\Users\User;
use App\Models\Customers\Customer;
use App\Models\JournalSetups\CostCenter;

use App\Extenders\Models\BaseModel as Model;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;

class CustomerInvoiceLine extends Model
{
	protected $casts = [
        'product' => 'object',
		'variant' => 'object',
    ];

    protected $appends = [ 'removeUrl', 'approveUrl', 'rejectUrl', 'formatted_created_date', 'formatted_updated_date', 'creator', 'updater', 'is_approved'];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'customer_invoice_number' => $this->customer_invoice_number,
	        'customer_invoice_line_number' => $this->customer_invoice_line_number,
	        'sales_order_line_number' => $this->sales_order_line_number,
	        'sales_order_number' => $this->sales_order_number,
	    ];
	}
	
    /**
	 * Relationships
	 */
	
	public function product_relation()
	{
		return $this->belongsTo(Product::class, 'product_id', 'id')->withTrashed();
	}
	
    public function variant_relation() {
		return $this->belongsTo(Variant::class, 'variant_id', 'id')->withTrashed();
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customer_account', 'customer_account')->withTrashed();
	}

	public function cost_center()
	{
		return $this->belongsTo(CostCenter::class, 'cost_center', 'code')->withTrashed();
	}

	public function created_by_user()
	{
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user()
	{
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	/**
	 * Renderers
	 */
	
	public function removeUrl() {
		return route('customer-invoice-lines.archive', $this->id);
	}

	public function renderApproveUrl() {
		return route('customer-invoice-lines.approve', $this->id);
	}

	public function renderRejectUrl() {
		return route('customer-invoice-lines.reject', $this->id);
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

	public function getFormattedCreatedDateAttribute() {
		return Carbon::parse($this->created_at)->format('M d, Y');
	}

	public function getFormattedUpdatedDateAttribute() {
		return Carbon::parse($this->updated_at)->format('M d, Y');
	}

	public function getCreatorAttribute() {
		return $this->created_by_user->fullname;
	}

	public function getUpdaterAttribute() {
		return $this->created_by_user->fullname;
	}

	public function getIsApprovedAttribute() {
		return $this->approved_on ? true : false;
	}
}
