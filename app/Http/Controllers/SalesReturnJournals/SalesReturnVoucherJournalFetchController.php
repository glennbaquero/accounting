<?php

namespace App\Http\Controllers\SalesReturnJournals;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Journals\SalesOrderReturnJournal;
use App\Models\JournalLines\SalesReturnJournalVoucher;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

use App\Models\AdminSetups\Client;
use App\Models\Customers\Customer;
use Carbon\Carbon;

class CustomerInvoiceVoucherJournalFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new SalesReturnJournalVoucher;
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
        
        if($this->request->filled('id')) {
            $query = $query->where('customer_invoice_journal_number', $this->request->id);
        }

        if($this->request->filled('customer_invoice_number')) {
            $query = $query->where('customer_invoice_number', $this->request->customer_invoice_number);
        }

        if($this->request->filled('customer_account')) {
            $query = $query->where('customer_account', $this->request->customer_account);
        }
        
        return $query->orderBy('entry_pair_number');
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

            $main_account = null;
            $offset_account = null;
            
            if($item->main_account && $item->main_account != '---') {
                $main_account = MainAccount::withTrashed()->findOrFail($item->main_account);
                $normal_balance = $main_account->balance_control;
                $increase_rule = $main_account->db_cr_requirement;
                $decrease_rule = $main_account->db_cr_proposal;
               
            }else if($item->offset_account_ma && $item->offset_account != '---'){
                
                $offset_account = MainAccount::withTrashed()->findOrFail($item->offset_account);
                
                if($offset_account) {
                    $normal_balance = $offset_account->balance_control;
                    $increase_rule = $offset_account->db_cr_requirement;
                    $decrease_rule = $offset_account->db_cr_proposal;
                }
            }

            array_push($result, [
                'client' => $item->client ? $item->client->name : '---',
                'transaction_date' => $item->invoice_date ? Carbon::parse($item->invoice_date)->format('m/d/Y') : null,
                'id' => $item->id,
                'entry_pair_number' => $item->entry_pair_number,
                'client_id' => $item->client_id,
                'voucher_number' => $item->voucher_number,
                'journal_batch_number' => $item->journal_batch_number,
                'journal_name' => $item->journal_name,
                'voucher_line_number' => $item->voucher_line_number,
                'voucher_date' => $item->voucher_date ? Carbon::parse($item->voucher_date)->format('m/d/Y') : null,
                'balance_journal' => $item->balance_journal,
                'balance_journal_per_voucher' => $item->balance_journal_per_voucher,
                'total_debit_journal' => $item->total_debit_journal,
                'total_credit_journal' => $item->total_credit_journal,
                'total_debit_per_voucher' => $item->total_debit_per_voucher,
                'total_credit_per_voucher' => $item->total_credit_per_voucher,
                'description' => $item->description ?? '---',
                'debit_amount' => $item->debit_amount,
                'credit_amount' => $item->credit_amount,
                'approved_date' => $item->approved_date ? Carbon::parse($item->approved_date)->format('m/d/Y') : null,
                'reported_as_ready_by_journal' => $item->reported_as_ready_by_journal,
                'approved_by_journal' => $item->approved_by_journal,
                'rejected_by_journal' => $item->rejected_by_journal,
                'review_date_trans' => $item->review_date_trans,
                'approved_by_id_trans' => $item->approved_by_id_trans,
                'approved_by_name_trans' => $item->approved_by_name_trans,
                'posted_checkbox' => $item->posted_checkbox,
                'posted_on' => $item->posted_on,
                'posted_by' => $item->posted_by,
                'customer_invoice_number' => $item->customer_invoice_number,
                'invoice_number' => $item->invoice_number,
                'invoice_date' => $item->invoice_date ? Carbon::parse($item->invoice_date)->format('m/d/Y') : null,
                'due_date' => $item->due_date ? Carbon::parse($item->due_date)->format('m/d/Y') : null,
                'invoice_payment_release_date' => $item->invoice_payment_release_date ? Carbon::parse($item->invoice_payment_release_date)->format('m/d/Y') : null,
                'pending_customer_invoice' => $item->pending_customer_invoice,
                'customer_account' => $item->customer_account,
                'customer_name' => $item->customer_name,
                'payment_id' => $item->payment_id,
                'method_of_payment' => $item->method_of_payment,
                'terms_of_payment' => $item->terms_of_payment,
                'bank_transaction_type' => $item->bank_transaction_type,
                'bank_account' => $item->bank_account,
                'payment_specification' => $item->payment_specification,
                'payment_deposit_slip' => $item->payment_deposit_slip,
                'sales_order' => $item->sales_order,
                'main_account' => $item->main_account ? $item->main_account : '---',
                'main_account_name' => isset($main_account->main_account_name) ? $main_account->main_account_name : '---',
                'account_type' => $item->account_type,
                'offset_company_accounts' => $item->offset_company_accounts,
                'offset_account_type' => $item->offset_account_type,
                'offset_account' => $item->offset_account,
                // 'offset_account_name' => $item->offset_account_ma ? $offset_account->main_account_name : '---',
                'offset_account_name' => isset($offset_account->main_account_name) ? $offset_account->main_account_name : '---',
                'offset_account_id' => $item->offset_account,
                'offset_transaction_text' => $item->offset_transaction_text,
                'charges_percentage' => $item->charges_percentage,
                'cash_discount_code' => $item->cash_discount_code,
                'cash_discount_date' => $item->cash_discount_date ? Carbon::parse($item->cash_discount_date)->format('m/d/Y') : null,
                'cash_discount_amount' => $item->cash_discount_amount,
                'release_date_comment' => $item->release_date_comment,
                'tax_exempt_number' => $item->tax_exempt_number,
                'sales_tax_included_in_amount' => $item->sales_tax_included_in_amount,
                'calculated_sales_tax_amount' => $item->calculated_sales_tax_amount,
                'sales_tax_code' => $item->sales_tax_code,
                'sales_tax_direction' => $item->sales_tax_direction,
                'sales_tax_group' => $item->sales_tax_group,
                'item_sales_tax_group' => $item->item_sales_tax_group,
                'actual_tax_amount' => $item->actual_tax_amount,
                'created_by' => $item->created_by,
                'created_date' =>  $item->created_at ? Carbon::parse($item->created_at)->format('M. d, Y') : '---',
                'updated_date' =>  $item->updated_at ? Carbon::parse($item->updated_at)->format('M. d, Y') : '---',
                'updated_by' => $item->updated_by,
                'log_message' => $item->log_message ?? '---',
                'logged_by' => $item->logged_by ?? '---',
                'log_date' => $item->log_date ? Carbon::parse($item->log_date)->format('m/d/Y') : null,
                'alreadyInSelectedItem' => false,
                'updateUrl' => $item->updateUrl,

                'normal_balance' => $normal_balance,
                'increase_rule' => $increase_rule,
                'decrease_rule' => $decrease_rule,

                'selected' => false,

                'posted_by_name' => $item->posted_by_user ? $item->posted_by_user->fullname : null,
                'balance' => $item->balance_journal,

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

        if ($id) {
            $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);

            $voucher_lines = $item->getVoucherLines();
        }

        return response()->json([
            'item' => $item,
            'voucher_lines' => $voucher_lines,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
