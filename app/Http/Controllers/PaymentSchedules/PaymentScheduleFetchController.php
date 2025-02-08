<?php

namespace App\Http\Controllers\PaymentSchedules;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\PaymentSchedules\PaymentSchedule;
use App\Models\Invoices\CustomerInvoice;
use App\Models\BillsExchanges\BillsExchange;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\Customer;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\Vendors\Vendor;
use App\Models\Invoices\VendorInvoice;
use App\Models\PromissoryNotes\PurchasePromissoryNote;

use Carbon\Carbon;

class PaymentScheduleFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new PaymentSchedule;
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

                'payment_schedule_name' => $item->payment_schedule_name,
                'description' => $item->description,
                'schedule_start_date' => $item->schedule_start_date,
                'schedule_end_date' => $item->schedule_end_date,
                'allocation' => $item->allocation,
                'payment_per' => $item->payment_per,
                'no_of_payments' => $item->no_of_payments,
                'principal_original_amount' => $item->principal_original_amount,
                'minimum_amount' => $item->minimum_amount,
                'sales_tax_allocation' => $item->sales_tax_allocation,
                'charge_allocation' => $item->charge_allocation,
                
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
        $invoices = CustomerInvoice::getCompanyData();
        $bills_exchanges = BillsExchange::getCompanyData();
        $customers = Customer::getCompanyData();
        $client_banks = ClientBankAccount::getCompanyData();
        $terms_of_payments = TermsOfPayment::getCompanyData();

        $vendors = Vendor::getCompanyData();
        $vendor_invoices = VendorInvoice::getCompanyData();
        $promissory_notes = PurchasePromissoryNote::getCompanyData();

        if ($id) {
            $item = PaymentSchedule::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'invoices' => $invoices,
            'bills_exchanges' => $bills_exchanges,
            'customers' => $customers,
            'client_banks' => $client_banks,
            'terms_of_payments' => $terms_of_payments,
            'vendors' => $vendors,
            'vendor_invoices' => $vendor_invoices,
            'promissory_notes' => $promissory_notes,
        ]);
    }

    protected function formatView($item)
    {
        $item->approveUrl = $item->renderApproveUrl();
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->approved_by = $item->renderApprovedBy();
        $item->customerInvoiceUrl = $item->renderCustomerInvoiceUrl();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
