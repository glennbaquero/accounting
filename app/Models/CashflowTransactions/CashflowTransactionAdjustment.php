<?php

namespace App\Models\CashflowTransactions;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\BankAccountStatements\BankAccountStatementLine;

class CashflowTransactionAdjustment extends Model
{
	protected $casts = [
		'posted_checkbox' => 'boolean',
		'voucher_date' => 'date',
		'check_number_issued' => 'date',
		'main_account' => 'integer',
	];
	
	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'cashflow_transaction_id',
		
		'cashflow_transaction_name',
		'type',
		'vendor_payment_journal_voucher',
		'vendor_payment_journal_number',
		'customer_payment_journal_voucher',
		'customer_payment_journal_number',
		'journal_name',
		'voucher_date',
		'description',
		'debit_amount',
		'credit_amount',
		'posted_checkbox',
		'posted_on',
		'posted_by',
		'vendor_account',
		'vendor_name',
		'vendor_invoice_number',
		'customer_invoice_number',
		'invoice_date',
		'payment_due_date',
		'settlement_type',
		'method_of_payment_customer',
		'method_of_payment_vendor',
		'vendor_payment_id',
		'customer_payment_id',
		'payment_status',
		'deposit_slip_number',
		'payment_specification',
		'payment_reference',
		'bank_transaction_type',
		'bank_account',
		'postdated_check_status',
		'check_number',
		'check_number_issued',
		'maturity_date',
		'received_date',
		'cashier',
		'salesperson',
		'issuing_bank_branch',
		'issuing_bank_name',
		'stop_payment',
		'replacement_check',
		'original_check',
		'check_amount',
		'recipient_name',

		'main_account',
		'account_type',
		'offset_company_accounts',
		'offset_account_type',
		'offset_account',
		'offset_transaction_text',
		'sales_tax_direction',
		'sales_tax_group',
		'item_sales_tax_group',
		'withholding_tax_group',
		'fee_account',
		'fee_id',
		'fee_amount',
	])
	{

		$vars = $request->only($columns);

		unset($vars['posted_checkbox']);

	    if (!$item) {
	        $item = static::create($vars);
	        $cashflow_adjustment_id = 'cash-register-adjustment-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'cashflow_adjustment_id' => $cashflow_adjustment_id,
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
     * Relationships
     */
    public function approved_by_user() {
    	return $this->belongsTo(User::class, 'approved_by')->withTrashed();
    }

    public function posted_by_user() {
        return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
    }
    
    public function created_by_user() {
    	return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
    
    public function updated_by_user() {
    	return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    public function offset_account_ma() {
        return $this->belongsTo(MainAccount::class, 'offset_account', 'id')->withTrashed();
    }

    public function transaction() {
    	return $this->belongsTo(CashflowTransaction::class, 'cashflow_transaction_id', 'cashflow_transaction_id')->withTrashed();
    }

    public function adjusted_by_user() {
        return $this->belongsTo(User::class, 'adjustment_by')->withTrashed();
    }

    /**
     * Renders
     */
    public function renderAdjustedBy() {
		return $this->adjusted_by_user ? $this->adjusted_by_user->renderName() : ''; 
	}

    public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderApprovedBy() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return '';
    }

    public function renderArchiveUrl() {
        return '';
    }

    public function renderRestoreUrl() {
        return '';
    }

    public function statementLines() {
		return $this->hasMany(BankAccountStatementLine::class, 'payment_reference', 'payment_reference');
	}

	public function renderEndingBalance() {
		$statementLine = $this->statementLines()->first();
		return isset($statementLine->statement->ending_balance) ? $statementLine->statement->ending_balance : 0;
	}
}
