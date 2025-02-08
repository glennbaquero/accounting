<?php

namespace App\Http\Controllers\CustomerInvoiceJournals;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Journals\CustomerInvoiceJournal;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

use App\Models\AdminSetups\Client;
use App\Models\Customers\Customer;

use App\Models\Invoices\CustomerInvoice;

use Carbon\Carbon;

class CustomerInvoiceJournalFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CustomerInvoiceJournal;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        /**
         * Queries
         * 
         */
        
        if($this->request->filled('show') && $this->request->show != 'All') {
            $query = $query->where('journal_status', $this->request->show);

            switch ($this->request->show) {
                case 'Open':
                    $query = $query->whereNull('posted_by');
                    break;
                
                default:
                    $query = $query->whereNotNull('posted_by');
                    break;
            }
        }
        
        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        if($this->request->filled('status')) {
            $status = $this->request->status;
            if($status == 'approved') {
                $query = $query->whereNotNull('approved_date')->where('general_journal_number', $this->request->id);
            }
            if($status == 'rejected') {
                $query = $query->whereNotNull('rejected_by_journal')->where('general_journal_number', $this->request->id);
            }
            if($status == 'pending') {
                $query = $query->whereNull('rejected_by_journal')->whereNull('approved_date')->where('general_journal_number', $this->request->id);
            }
        }

        return $query->withTrashed()->where('company_id', auth()->user()->company_id);
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            // $data = $this->formatItem($item);
            // $data = array_merge($data, [
            //     'id' => $item->id,
            //     'deparment' =>
            //     'created_at' => $item->renderDate(),
            //     'deleted_at' => $item->deleted_at,
            // ]);

            array_push($result, [
                'id' => $item->id,
                'client' => $item->client ? $item->client->name : '---',
                'customer_invoice_journal_number' => $item->customer_invoice_journal_number,
                'invoice_journal_batch_number' => $item->invoice_journal_batch_number,
                'journal_name_number' => $item->journal_name_number,
                'journal_name' => $item->journal_name,
                'description' => $item->description,
                'journal_status' => $item->journal_status,
                'balance_journal' => $item->balance_journal,
                'total_debit_journal' => $item->total_debit_journal,
                'total_credit_journal' => $item->total_credit_journal,
                'reported_as_ready_by_journal' => $item->reported_as_ready_by_journal,
                'approved_by_journal' => $item->approved_by_journal,
                'rejected_by_journal' => $item->rejected_by_journal,
                'posted_checkbox' => $item->posted_checkbox,
                'posted_on' => $item->posted_on,
                'posted_by' => $item->posted_by,
                'log_in_checkbox' => $item->log_in_checkbox,
                'log_message' => $item->log_message ?? '---',
                'reversing_entry_checkbox' => $item->reversing_entry_checkbox,
                'reversing_date' => $item->reversing_date,
                'original_journal_number' => $item->original_journal_number,
                'show_user_created_only' => $item->show_user_created_only,
                'journal_type' => $item->journal_type,
                'account_type' => $item->account_type,
                'offset_account' => $item->offset_account,
                'document' => $item->document,
                'detail_level' => $item->detail_level,
                'posting_layer' => $item->posting_layer,
                'number_allocation_at_posting' => $item->number_allocation_at_posting,
                'delete_lines_after_posting' => $item->delete_lines_after_posting,
                'lines_limit' => $item->lines_limit,
                'amounts_include_sales_tax' => $item->amounts_include_sales_tax,
                'remittance_type' => $item->remittance_type,
                'bank_account' => $item->bank_account,
                'protest_settlements' => $item->protest_settlements,
                'protest_settled_process' => $item->protest_settled_process,
                'financial_dimensions' => $item->financial_dimensions,
                'in_use_checkbox' => $item->in_use_checkbox,
                'used_by_user' => $item->used_by_user,
                'locked_by_system' => $item->locked_by_system,
                'private_for_user_group' => $item->private_for_user_group,
                'created_by' => $item->created_by,
                'updated_by' => $item->updated_by,
                'deleted_at' => $item->deleted_at,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'cost_center' => $item->cost_center,
                'department' => $item->department,
                'expense_purpose' => $item->expense_purpose,
                'approved_date' => $item->approved_date,
                'rejected_date' => $item->rejected_date,


                'department_fd' => $item->department_fd->dimension_name,
                'validateUrl' => $item->renderValidateUrl(),

                'is_selected' => false,
                'updateUrl' => $item->renderUpdateUrl(),
                'createLineUrl' => $item->renderCreateLineUrl(),
                'archiveUrl' => $item->renderArchiveUrl(),
                'showUrl' => $item->renderShowUrl(),
                'restoreUrl' => $item->renderRestoreUrl(),
                'editUrl' => $item->renderEditUrl(),
                'totalDebit' => number_format($item->customer_invoice_approval_journal_vouchers->sum('debit_amount'), 2, '.', ','),
                'totalCredit' => number_format($item->customer_invoice_approval_journal_vouchers->sum('credit_amount'), 2, '.', ','),
                'totalBalance' => number_format($item->customer_invoice_approval_journal_vouchers->sum('balance_journal'), 2, '.', ','),
                'total_vouchers' => $item->customer_invoice_approval_journal_vouchers()->count(),
                'total_log_errors' => $item->customer_invoice_approval_journal_vouchers()->where('log_message', 'like', '%Error%')->count(),
            ]);
        }

        return $result;
    }

    /**
     * Build array data
     * 
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $payment_methods = PaymentMethod::get();
        $terms_of_payments = TermsOfPayment::get();
        $voucher_lines = [];
        $cost_centers = FinancialDimension::where('use_value_from', 'Cost centers')->first()->financial_dimension_values;
        $departments = FinancialDimension::where('use_value_from', 'Departments')->first()->financial_dimension_values;
        $expense_purposes = FinancialDimension::where('use_value_from', 'Expense purposes')->first()->financial_dimension_values;
        $customers = Customer::get();
        $main_accounts = MainAccount::get();
        $clients = User::getClients();
        $invoices = CustomerInvoice::where('company_id', auth()->user()->company_id)->get();

        if ($id) {
            $item = CustomerInvoiceJournal::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
            $item['reversing_date'] = Carbon::parse($item->reversing_date)->format('m/d/Y');

            $voucher_lines = $item->getVoucherLines();
        }

        return response()->json([
            'item' => $item,
            'payment_methods' => $payment_methods,
            'terms_of_payments' => $terms_of_payments,
            'voucher_lines' => $voucher_lines,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'main_accounts' => $main_accounts,
            'customers' => $customers,
            'clients' => $clients,
            'invoices' => $invoices
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->total_logs = $item->customer_invoice_approval_journal_vouchers()->where('log_message', 'like', '%Error%')->count();
        $item->total_vouchers = $item->customer_invoice_approval_journal_vouchers()->count();

        return $item;
    }
}
