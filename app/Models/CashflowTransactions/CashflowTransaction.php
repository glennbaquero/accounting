<?php

namespace App\Models\CashflowTransactions;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\BankAccountStatements\BankAccountStatementLine;

class CashflowTransaction extends Model
{
	protected $casts = [
		'posted_checkbox' => 'boolean',
		'voucher_date' => 'date',
		'check_number_issued' => 'date',
		'main_account' => 'integer',
	];

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
			'id' => $this->id,
    	    'cashflow_transaction_id' => $this->cashflow_transaction_id,
			'cashflow_transaction_name' => $this->cashflow_transaction_name,
			'type' => $this->type,
			'vendor_payment_journal_voucher' => $this->vendor_payment_journal_voucher,
			'vendor_payment_journal_number' => $this->vendor_payment_journal_number,
			'customer_payment_journal_voucher' => $this->customer_payment_journal_voucher,
			'customer_payment_journal_number' => $this->customer_payment_journal_number,
			'journal_name' => $this->journal_name,
			'voucher_date' => $this->voucher_date,
			'description' => $this->description,
			'debit_amount' => $this->debit_amount,
			'credit_amount' => $this->credit_amount,
			'posted_checkbox' => $this->posted_checkbox,
			'posted_on' => $this->posted_on,
			'posted_by' => $this->posted_by,
			'vendor_account' => $this->vendor_account,
			'vendor_name' => $this->vendor_name,
			'vendor_invoice_number' => $this->vendor_invoice_number,
			'customer_invoice_number' => $this->customer_invoice_number,
			'invoice_date' => $this->invoice_date,
			'payment_due_date' => $this->payment_due_date,
			'settlement_type' => $this->settlement_type,
			'method_of_payment' => $this->method_of_payment,
			'vendor_payment_id' => $this->vendor_payment_id,
			'customer_payment_id' => $this->customer_payment_id,
			'payment_status' => $this->payment_status,
			'deposit_slip_number' => $this->deposit_slip_number,
			'payment_specification' => $this->payment_specification,
			'payment_reference' => $this->payment_reference,
			'bank_transaction_type' => $this->bank_transaction_type,
			'bank_account' => $this->bank_account,
			'postdated_check_status' => $this->postdated_check_status,
			'check_number' => $this->check_number,
			'check_number_issued' => $this->check_number_issued,
			'maturity_date' => $this->maturity_date,
			'received_date' => $this->received_date,
			'cashier' => $this->cashier,
			'salesperson' => $this->salesperson,
			'issuing_bank_branch' => $this->issuing_bank_branch,
			'issuing_bank_name' => $this->issuing_bank_name,
			'stop_payment' => $this->stop_payment,
			'replacement_check' => $this->replacement_check,
			'original_check' => $this->original_check,
			'check_amount' => $this->check_amount,
			'recipient_name' => $this->recipient_name,
			'reconciled_checkbox' => $this->reconciled_checkbox,
			'reconciled_date' => $this->reconciled_date,
			'reconciled_by' => $this->reconciled_by,
			'adjustment_checkbox' => $this->adjustment_checkbox,
			'adjustment_date' => $this->adjustment_date,
			'adjustment_by' => $this->adjustment_by,
			'matched' => $this->matched,
			'main_account' => $this->main_account,
			'account_type' => $this->account_type,
			'offset_company_accounts' => $this->offset_company_accounts,
			'offset_account_type' => $this->offset_account_type,
			'offset_account' => $this->offset_account,
			'offset_transaction_text' => $this->offset_transaction_text,
			'sales_tax_direction' => $this->sales_tax_direction,
			'sales_tax_group' => $this->sales_tax_group,
			'item_sales_tax_group' => $this->item_sales_tax_group,
			'withholding_tax_group' => $this->withholding_tax_group,
			'fee_account' => $this->fee_account,
			'fee_id' => $this->fee_id,
			'fee_amount' => $this->fee_amount,
        ];
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
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
		// 'reconciled_checkbox',
		// 'reconciled_date',
		// 'reconciled_by',
		// 'adjustment_checkbox',
		// 'adjustment_date',
		// 'adjustment_by',
		'matched',
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
	        $cashflow_transaction_id = 'cash-register-transaction-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'cashflow_transaction_id' => $cashflow_transaction_id,
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
    public function adjustments() {
    	return $this->belongsTo(CashflowTransactionAdjustment::class, 'cashflow_transaction_id', 'cashflow_transaction_id')->withTrashed();
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

	public function renderShowUrl() {
        return route('cashflow-transactions.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('cashflow-transactions.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('cashflow-transactions.restore', $this->id);
    }

    public function statementLines() {
		return $this->hasMany(BankAccountStatementLine::class, 'payment_reference', 'payment_reference');
	}

	public function renderEndingBalance() {
		$statementLine = $this->statementLines()->first();
		return isset($statementLine->statement->ending_balance) ? $statementLine->statement->ending_balance : 0;
	}
}
