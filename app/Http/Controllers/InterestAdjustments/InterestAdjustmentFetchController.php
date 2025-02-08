<?php

namespace App\Http\Controllers\InterestAdjustments;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\InterestAdjustments\InterestAdjustment;
use App\Models\InterestNotes\InterestNote;
use App\Models\Customers\Customer;
use App\Models\PostingProfile\CustomerPostingProfile;

use Carbon\Carbon;

class InterestAdjustmentFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new InterestAdjustment;
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

        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        $query = $query->where('company_id', auth()->user()->company_id);
        
        return $query;
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\InterestAdjustment $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'interest_adjustment_id' => $item->interest_adjustment_id,
                'interest_adjustment_date' => $item->interest_adjustment_date,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'customer_account' => $item->customer_account,
                'customer' => $item->customer,
                'transaction_date' => $item->transaction_date,
                'transaction_type' => $item->transaction_type,
                'interest_note_id' => $item->interest_note_id,
                'interest_note_amount' => $item->interest_note_amount,
                'waived_amount' => $item->waived_amount,
                'unpaid_balance' => $item->unpaid_balance,
                'fee_amount' => $item->fee_amount,
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
        $customers = Customer::getCompanyData();
        $interest_notes = InterestNote::getCompanyData();
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $posting_profiles = CustomerPostingProfile::get();

        if ($id) {
            $item = InterestAdjustment::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'customers' => $customers,
            'interest_notes' => $interest_notes,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'posting_profiles' => $posting_profiles,
        ]);
    }

    protected function formatView($item)
    {
        $item->approveUrl = $item->renderActionUrl('approve');
        $item->waiveUrl = $item->renderActionUrl('waive');
        $item->reinstateUrl = $item->renderActionUrl('reinstate');
        $item->reserveUrl = $item->renderActionUrl('reserve');
        $item->postUrl = $item->renderActionUrl('post');

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->approved_by = $item->renderApprovedBy();
        $item->waived_by = $item->renderWaivedBy();
        $item->reinstated_by = $item->renderReinstatedBy();
        $item->reserved_by = $item->renderReservedBy();
        $item->posted_by = $item->renderPostedBy();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
