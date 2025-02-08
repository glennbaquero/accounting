<?php

namespace App\Http\Controllers\InterestNotes;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Customers\Customer;
use App\Models\InterestNotes\InterestNote;
use App\Models\Invoices\CustomerInvoice;
use App\Models\PostingProfile\CustomerPostingProfile;
use App\Models\FinancialDimensions\FinancialDimension;

use Carbon\Carbon;

class InterestNoteFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new InterestNote;
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
     * @param Illuminate\Support\InterestNote $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'interest_note' => $item->interest_note,
                'interest_date' => $item->interest_date,
                'interest_updated_date' => $item->interest_updated_date,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'days' => $item->days,
                'description' => $item->description,
                'interest_note_voucher' => $item->interest_note_voucher,
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
        $customers = Customer::all();
        $posting_profiles = CustomerPostingProfile::getCompanyData();
        $invoices = CustomerInvoice::getCompanyData();
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');

        if ($id) {
            $item = InterestNote::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'customers' => $customers,
            'posting_profiles' => $posting_profiles,
            'invoices' => $invoices,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
        ]);
    }

    protected function formatView($item)
    {
        $item->postUrl = $item->renderPostUrl();

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->posted_by = $item->renderPostedBy();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
