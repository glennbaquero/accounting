<?php

namespace App\Http\Controllers\Vendors;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Client;
use App\Models\JournalSetups\PaymentDay;
use App\Models\Vendors\Vendor;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\Users\User;

class VendorFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Vendor;
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
                'client' => $item->client ? $item->client->name : '---',
                'vendor_id' => $item->vendor_account,
                'company_name' => $item->company_name,
                'name' => $item->renderName(),
                'display_name' => $item->renderDisplayName(),
                'company' => $item->company,
                'telephone_number' => $item->phone,
                'mobile_number' => $item->mobile_number,
                'address' => $item->address,
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
        $payment_methods = PaymentMethod::where('company_id', auth()->user()->company_id)->get();
        $terms_of_payments = TermsOfPayment::where('company_id', auth()->user()->company_id)->get();
        $payment_days = PaymentDay::where('company_id', auth()->user()->company_id)->get();
        $clients = User::getClients();

        if ($id) {
            $item = Vendor::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'payment_methods' => $payment_methods,
            'terms_of_payments' => $terms_of_payments,
            'payment_days' => $payment_days
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->mobile_number_component = $item->mobile_calling_code ? explode($item->mobile_calling_code,$item->mobile_number)[1] : $item->mobile_number;
        $item->phone_number_component = $item->phone_calling_code ? explode($item->phone_calling_code,$item->phone)[1] : $item->phone;

        return $item;
    }
}
