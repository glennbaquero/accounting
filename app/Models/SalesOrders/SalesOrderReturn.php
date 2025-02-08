<?php

namespace App\Models\SalesOrders;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use App\Models\JournalSetups\CostCenter;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\Invoices\CustomerInvoice;
use App\Models\Customers\Customer;
use App\Models\FinancialDimensions\FinancialDimensionValue;

class SalesOrderReturn extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'sales_order_numb_returner' => $this->sales_order_return_number,
	        'sales_order_date' => $this->sales_order_date,
	        'delivery_date' => $this->delivery_date,
	    ];
	}

	/**
	 * Relationships
	 */
	

	public function client() {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	public function customer() {
		return $this->belongsTo(Customer::class, 'customer_account', 'customer_account')->withTrashed();
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

	public function payment_method() {
		return $this->belongsTo(PaymentMethod::class, 'method_of_payment', 'id')->withTrashed();
	}

	public function terms_of_payment() {
		return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment', 'terms_of_payment')->withTrashed();
	}

	public function confirmed_by_user() {
		return $this->belongsTo(User::class, 'confirmed_by', 'id')->withTrashed();
	}

	public function sales_order_return_lines() {
		return $this->hasMany(SalesOrderReturnLine::class, 'sales_order_return_number', 'sales_order_return_number');
	}

	public function approved_by_user() {
		return $this->belongsTo(User::class, 'approver', 'id')->withTrashed();
	}

	public function customer_invoice() {
		return $this->hasOne(CustomerInvoice::class, 'sales_order_number', 'sales_order_return_number');
	}

	public function customer_invoices() {
		return $this->hasMany(CustomerInvoice::class, 'sales_order_number', 'sales_order_return_number');
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}
	
	public function department_value() {
		return $this->belongsTo(FinancialDimensionValue::class, 'department');
	}

	public function terms_of_payment_detail() {
		return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['sales_order_return_number', 'customer_account', 'invoice_account', 'sales_order_date', 'delivery_date', 'due_date', 'approval_status_date', 'confirmed_date', 'accounting_date', 'customer_name', 'customer_address', 'customer_contact_id', 'confirmed_by', 'approver', 'ordered_by', 'created_by', 'updated_by', 'cost_center_id', 'department_id', 'expense_purpose_id', 'posting_profile', 'accounting_distribution', 'method_of_payment', 'terms_of_payment', 'payment_specification', 'sales_tax_group', 'tax_exempt_number', 'sales_type', 'sales_order_status', 'document_status', 'approval_status', 'settlement_type', 'prices_include_sales_tax', 'delivery_terms', 'mode_of_delivery', 'charges_group', 'cash_discount', 'line_discount_group', 'multiline_disc_group', 'total_discount_group', 'update_quantity_type', 'total_data_quantity', 'total_data_volume', 'total_line_discount', 'subtotal_amount', 'total_discount', 'total_charges', 'total_sales_tax', 'total_round_off', 'total_amount', 'total_cash_discount', 'sales_orders', 'sold_by', 'client_id'])
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
        return route('sales-order-returns.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('sales-order-returns.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('sales-order-returns.restore', $this->id);
    }

    public function renderConfirmationUrl() {
        return route('sales-order-returns.confirmation', $this->id);
    }

    public function renderCustomerInvoiceUrl() {
        return route('customer-invoices.create', $this->sales_order_return_number);
    }

    public function renderCOP() {
    	$amount = 0.00;
    	$lines = $this->sales_order_return_lines;
    	
    	if($lines->count()) {
    		$amount = $lines->sum('charge_on_sales');
    	}
    	return number_format($amount, 2, '.', ',');
    }

    public function renderSubtotal() {
    	$amount = 0.00;
    	$lines = $this->sales_order_return_lines;
    	
    	if($lines->count()) {
    		$amount = $lines->sum('unit_price') *  $lines->sum('quantity') ;
    	}
    	return number_format($amount, 2, '.', ',');
    }

    public function renderTotalDiscount() {
    	$amount = 0.00;
    	$lines = $this->sales_order_return_lines;
    	
    	if($lines->count()) {
    		$amount = $lines->sum('discount');
    	}
    	return number_format($amount, 2, '.', ',');
    }

	public function renderTotalAmount() {
		$amount = 0.00;
		$lines = $this->sales_order_return_lines;
		
		if($lines->count()) {
			$amount = $lines->sum('amount') + $lines->sum('charge_on_purchase');
		}

		return number_format($amount, 2, '.', ',');
	}
}
