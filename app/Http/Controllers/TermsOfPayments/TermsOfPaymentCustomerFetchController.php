<?php

namespace App\Http\Controllers\TermsOfPayments;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\JournalSetups\TermsOfPaymentCustomer;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\JournalSetups\PaymentDay;
use App\Models\PostingProfile\CustomerPostingProfile;
use App\Models\PaymentSchedules\PaymentSchedule;

class TermsOfPaymentCustomerFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new TermsOfPaymentCustomer;
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

        return $query->where('company_id', auth()->user()->company_id);
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
                'terms_of_payment' => $item->terms_of_payment,
                'payment_method' => $item->payment_method ? $item->payment_method->method_of_payment : '---',
                'description' => str_limit($item->description, 15),
                'payment_day' => $item->payment_day_elo ? $item->payment_day_elo->payment_day : '---',
                'cutoff_day' => $item->cutoff_day,
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

        $payment_methods = CustomerPaymentMethod::getCompanyData();
        $payment_days = PaymentDay::getCompanyData();
        $ledger_postings = CustomerPostingProfile::getCompanyData();
        $payment_schedules = PaymentSchedule::getCompanyData();

        if ($id) {
            $item = TermsOfPaymentCustomer::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'payment_methods' => $payment_methods,
            'payment_days' => $payment_days,
            'ledger_postings' => $ledger_postings,
            'payment_schedules' => $payment_schedules,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
