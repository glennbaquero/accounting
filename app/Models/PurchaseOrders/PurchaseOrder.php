<?php

namespace App\Models\PurchaseOrders;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Vendors\Vendor;
use App\Models\Users\User;
use App\Models\JournalSetups\CostCenter;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\Invoices\VendorInvoice;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\Letters\LetterOfGuarantee;

class PurchaseOrder extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'purchase_order_number' => $this->purchase_order_number,
	        'purchase_order_date' => $this->purchase_order_date,
	        'delivery_date' => $this->delivery_date,
	    ];
	}

	/**
	 * Relationships
	 */

	public function payment_method() {
		return $this->belongsTo(VendorPaymentMethod::class, 'method_of_payment', 'method_of_payment_id')->withTrashed();
	}

	
	public function client() {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	public function vendor() {
		return $this->belongsTo(Vendor::class, 'vendor_account', 'vendor_account')->withTrashed();
	}

	public function cost_center() {
		return $this->belongsTo(CostCenter::class, 'cost_center', 'code')->withTrashed();
	}

	public function terms_of_payment_detail() {
		return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment', 'id')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}

	public function purchase_order_lines() {
		return $this->hasMany(PurchaseOrderLine::class, 'purchase_order_number', 'purchase_order_number');
	}

	public function confirmed_by_user() {
		return $this->belongsTo(User::class, 'confirmed_by', 'id')->withTrashed();
	}

	public function approved_by_user() {
		return $this->belongsTo(User::class, 'approver', 'id')->withTrashed();
	}

	public function vendor_invoice() {
		return $this->hasOne(VendorInvoice::class, 'purchase_order_number', 'purchase_order_number');
	}

	public function vendor_invoices() {
		return $this->hasMany(VendorInvoice::class, 'purchase_order_number', 'purchase_order_number');
	}

	public function department_value() {
		return $this->belongsTo(FinancialDimensionValue::class, 'department');
	}

	public function credits() {
		return $this->hasMany(LetterCreditPurchase::class);
	}

	public function guarantees() {
		return $this->hasMany(LetterOfGuarantee::class);
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['posting_profile_id' ,'sales_order_number' ,'purchase_order_number', 'vendor_account', 'invoice_account', 'purchase_order_date', 'delivery_date', 'due_date', 'confirmed_date', 'accounting_date', 'vendor_name', 'vendor_address', 'vendor_contact_id', 'confirmed_by', 'approver', 'ordered_by', 'created_by', 'updated_by', 'cost_center', 'department', 'expense_purpose', 'posting_profile', 'accounting_distribution', 'method_of_payment', 'terms_of_payment', 'payment_specification', 'sales_tax_group', 'tax_exempt_number', 'purchase_type', 'purchase_order_status', 'document_status', 'approval_status', 'settlement_type', 'prices_include_sales_tax', 'delivery_terms_type', 'mode_of_delivery_type', 'charges_group', 'cash_discount', 'line_discount_group', 'multiline_disc_group', 'total_discount_group', 'update_quantity_type', 'total_data_quantity', 'total_data_volume', 'total_line_discount', 'subtotal_amount', 'total_discount', 'total_charges', 'total_sales_tax', 'total_round_off', 'total_amount', 'total_cash_discount','tax_exempt_number', 'method_of_payment', 'term_of_payment', 'accouting_distribution', 'delivery_contact', 'delivery_address', 'invoice_onhold_checkbox', 'client_id', 'total_sales_vat_exclusive', 'less_discount', 'add_charge', 'add_vat', 'total_sales_vat_inclusive', 'less_withholding_tax', 'amount_due', 'vattable_sales', 'vat_exempt_sale', 'zero_rated_sales', 'vat_amount', 'total_amount_due', 'cash_amount', 'check_amount', 'deposit_amount', 'other_amount', 'total_amount_received', 'outstanding', 'tax_posting_id', 'payment_schedule_id', 'bank_document_type'])
	{
		
	    $vars = $request->only($columns);
	    
		$vars['vendor_address'] = $request->filled('vendor_address') ? $vars['vendor_address'] : '---';
	    $vars['one_time_supplier_checkbox'] = $request->filled('one_time_supplier_checkbox') ? true : false;
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);
	    }

	    return $item;
	}

	/**
	 * Methods
	 */

	public function renderTotalAmount() {
		$amount = 0.00;
		$lines = $this->purchase_order_lines;
		if($lines->count()) {
			$amount = $lines->sum('amount');
		}

		return $amount;
	}

	public function renderSubtotal() {
		$amount = 0.00;
		$lines = $this->purchase_order_lines;
		
		if($lines->count()) {
			$amount = $lines->sum('unit_price') *  $lines->sum('quantity') ;
		}

		return $amount;
	}

	public function renderTotalDiscount() {
		$amount = 0.00;
		$lines = $this->purchase_order_lines;
		
		if($lines->count()) {
			$amount = $lines->sum('discount');
		}

		return $amount;
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('purchase-orders.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('purchase-orders.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('purchase-orders.restore', $this->id);
    }

    public function renderConfirmationUrl() {
        return route('purchase-orders.confirmation', $this->id);
    }

    public function renderVendorInvoiceUrl() {
        return route('vendor-invoices.create', $this->purchase_order_number);
    }
}
