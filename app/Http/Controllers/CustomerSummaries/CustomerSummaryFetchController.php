<?php

namespace App\Http\Controllers\CustomerSummaries;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Customers\CustomerSummary;
use App\Models\Users\User;
use App\Models\Customers\Customer;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\JournalSetups\PaymentMethod;
use Carbon\Carbon;

class CustomerSummaryFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CustomerSummary;
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

        return $query;
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
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'customer_summary_id' => $item->customer_summary_id,
                'customer' => $item->customer->fullname,
                'issue_date_from' => Carbon::parse($item->issue_date_from)->format('m-d-y'),
                'issue_date_to' => Carbon::parse($item->issue_date_to)->format('m-d-y'),
                'prepared_by' => $item->prepared_by->fullname,
                'number_sales_order' => $item->number_sales_order,
                'number_customer_invoice' => $item->number_customer_invoice,
                'number_overdue_invoice' => $item->number_overdue_invoice,
                'opening_balance' => number_format($item->opening_balance, 2, '.', ','),
                'invoiced_amount' => number_format($item->invoiced_amount, 2, '.', ','),
                'amount_paid' => number_format($item->amount_paid, 2, '.', ','),
                'balance_due' => number_format($item->balance_due, 2, '.', ','),

                'created_at' => $item->renderDate(),
                'deleted_at' => $item->deleted_at,
            ]);

            array_push($result, $data);
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

        $method_of_payments = PaymentMethod::get();
        $terms_of_payments = TermsOfPayment::get();

        if ($id) {
            $item = CustomerSummary::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'users' => User::get(),
            'customers' => Customer::get(),
            'method_of_payments' => $method_of_payments,
            'terms_of_payments' => $terms_of_payments,

        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->approvedUrl = $item->renderApprovedUrl();
        $item->customer_summary_lines = $item->lines;

        return $item;
    }
}
