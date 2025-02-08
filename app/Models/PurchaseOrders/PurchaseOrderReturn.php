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

class PurchaseOrderReturn extends Model
{

	protected $table = 'purchase_order_returns';

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'purchase_order_return_number' => $this->purchase_order_return_number,
	        'purchase_order_return_date' => $this->purchase_order_return_date,
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

	// public function payment_method() {
	// 	return $this->belongsTo(PaymentMethod::class, 'method_of_payment', 'id')->withTrashed();
	// }

	public function terms_of_payment_detail() {
		return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment', 'id')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}

	public function purchase_order_return_lines() {
		return $this->hasMany(PurchaseOrderReturnLine::class, 'purchase_order_return_number', 'purchase_order_return_number');
	}

	public function confirmed_by_user() {
		return $this->belongsTo(User::class, 'confirmed_by', 'id')->withTrashed();
	}

	public function approved_by_user() {
		return $this->belongsTo(User::class, 'approver', 'id')->withTrashed();
	}

	public function vendor_invoice() {
		return $this->hasOne(VendorInvoice::class, 'purchase_order_number', 'purchase_order_return_number');
	}

	public function vendor_invoices() {
		return $this->hasMany(VendorInvoice::class, 'purchase_order_number', 'purchase_order_return_number');
	}

	public function department_value() {
		return $this->belongsTo(FinancialDimensionValue::class, 'department');
	}

	public function cancelled_by_user() {
		return $this->belongsTo(User::class, 'cancelled_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['sales_order_number', 'purchase_order_return_number', 'vendor_account', 'invoice_account', 'purchase_order_return_date', 'delivery_date', 'due_date', 'confirmed_date', 'accounting_date', 'vendor_name', 'vendor_address', 'vendor_contact_id', 'confirmed_by', 'approver', 'ordered_by', 'created_by', 'updated_by', 'cost_center', 'department', 'expense_purpose', 'posting_profile', 'accounting_distribution', 'method_of_payment', 'terms_of_payment', 'payment_specification', 'sales_tax_group', 'tax_exempt_number', 'purchase_type', 'purchase_order_status', 'document_status', 'approval_status', 'settlement_type', 'prices_include_sales_tax', 'delivery_terms_type', 'mode_of_delivery_type', 'charges_group', 'cash_discount', 'line_discount_group', 'multiline_disc_group', 'total_discount_group', 'update_quantity_type', 'total_data_quantity', 'total_data_volume', 'total_line_discount', 'subtotal_amount', 'total_discount', 'total_charges', 'total_sales_tax', 'total_round_off', 'total_amount', 'total_cash_discount','tax_exempt_number', 'method_of_payment', 'term_of_payment', 'accouting_distribution', 'delivery_contact', 'delivery_address', 'invoice_onhold_checkbox', 'client_id', 'total_sales_vat_exclusive', 'less_discount', 'add_charge', 'add_vat', 'total_sales_vat_inclusive', 'less_withholding_tax', 'amount_due', 'vattable_sales', 'vat_exempt_sale', 'zero_rated_sales', 'vat_amount', 'total_amount_due', 'cash_amount', 'check_amount', 'deposit_amount', 'other_amount', 'total_amount_received', 'outstanding', 'tax_posting_id'])
	{
		

	    $vars = $request->only($columns);

		$vars['vendor_address'] = $request->filled('vendor_address') ? $vars['vendor_address'] : '---';
		$vars['purchase_order_return_date'] = $request->purchase_order_date;
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
		$lines = $this->purchase_order_return_lines;
		
		if($lines->count()) {
			$amount = $lines->sum('amount') + $lines->sum('charge_on_purchase');
		}

		return number_format($amount, 2, '.', ',');
	}

	public function renderCOP() {
		$amount = 0.00;
		$lines = $this->purchase_order_return_lines;
		
		if($lines->count()) {
			$amount = $lines->sum('charge_on_purchase');
		}

		return number_format($amount, 2, '.', ',');
	}

	public function renderSubtotal() {
		$amount = 0.00;
		$lines = $this->purchase_order_return_lines;
		
		if($lines->count()) {
			$amount = $lines->sum('unit_price') *  $lines->sum('quantity') ;
		}

		return number_format($amount, 2, '.', ',');
	}

	public function renderTotalDiscount() {
		$amount = 0.00;
		$lines = $this->purchase_order_return_lines;
		
		if($lines->count()) {
			$amount = $lines->sum('discount');
		}

		return number_format($amount, 2, '.', ',');
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('purchase-order-returns.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('purchase-order-returns.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('purchase-order-returns.restore', $this->id);
    }

    public function renderConfirmationUrl() {
        return route('purchase-order-returns.confirmation', $this->id);
    }

    public function renderCancelUrl() {
        return route('purchase-order-returns.cancel', $this->id);
    }

    public function renderVendorInvoiceUrl() {
        return route('vendor-invoices.create', $this->purchase_order_return_number);
    }
}
