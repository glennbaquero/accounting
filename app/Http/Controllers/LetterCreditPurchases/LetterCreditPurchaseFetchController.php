<?php

namespace App\Http\Controllers\LetterCreditPurchases;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\PurchaseOrders\LetterCreditPurchase;
use App\Models\Users\User;
use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\PurchaseOrders\BankDocument;
use App\Models\Banks\BankFacilityType;
use App\Models\Invoices\PurchaseDeliveryReceipt;
use App\Models\Vendors\Vendor;
use App\Models\Vendors\VendorBankAccount;

use Carbon\Carbon;

class LetterCreditPurchaseFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new LetterCreditPurchase;
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
        $query = $query->where('company_id', auth()->user()->company_id);
        
        if($this->request->filled('client_id')) {
            $query = $query->where('client_id', $this->request->client_id);
        }

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
                'purchase_order_number' => $item->purchase_order->purchase_order_number,
                'bank_document_number' => $item->bank_document_number,
                'application_date' => $item->application_date ? Carbon::parse($item->application_date)->format('M d, Y') : '---',
                'receipt_date' => $item->receipt_date ? Carbon::parse($item->receipt_date)->format('M d, Y') : '---',
                'amendment_number' => $item->amendment_number,
                'amendment_on' => $item->amendment_on ? Carbon::parse($item->amendment_on)->format('M d, Y') : '---',
                'amendment_by' => $item->amendment_by_user->fullname,
                'purchase_status' => $item->purchase_status,
                'close' => $item->close ? Carbon::parse($item->close)->format('M d, Y') : '---',
                'confirmed' => $item->confirmed ? Carbon::parse($item->confirmed)->format('M d, Y') : '---',

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
        $users = User::where('company_id', auth()->user()->company_id)->get();
        $purchase_orders = PurchaseOrder::where('company_id', auth()->user()->company_id)->get();
        $vendors = Vendor::where('company_id', auth()->user()->company_id)->get();
        $banks = VendorBankAccount::where('company_id', auth()->user()->company_id)->get();
        $documents = BankDocument::where('company_id', auth()->user()->company_id)->get();
        $types = BankFacilityType::where('company_id', auth()->user()->company_id)->get();
        $delivery_receipts = PurchaseDeliveryReceipt::where('company_id', auth()->user()->company_id)->get();

        if($id) {
            $item = LetterCreditPurchase::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'users' => $users,
            'purchase_orders' => $purchase_orders,
            'vendors' => $vendors,
            'banks' => $banks,
            'documents' => $documents,
            'types' => $types,
            'delivery_receipts' => $delivery_receipts,
        ]);
    }

    protected function formatView($item)
    {
        $item->closeUrl = $item->renderCloseUrl();
        $item->confirmUrl = $item->renderConfirmUrl();
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->amendmentUrl = $item->renderAmendmentUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->confirmed_by = $item->confirmed_by_user ? $item->confirmed_by_user->fullname : null;
        $item->closed_by = $item->close_by_user ? $item->close_by_user->fullname : null;

        $item->confirmed_date = $item->confirmed ? Carbon::parse($item->confirmed)->format('M d, Y') : null;
        $item->closed_date = $item->close ? Carbon::parse($item->close)->format('M d, Y') : null;

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
