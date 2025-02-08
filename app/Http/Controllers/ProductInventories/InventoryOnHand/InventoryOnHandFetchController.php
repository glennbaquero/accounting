<?php

namespace App\Http\Controllers\ProductInventories\InventoryOnHand;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\ProductInventories\Products\Variant;
use App\Models\Users\User;

use App\Models\Inventories\InventoryOnHand;

class InventoryOnHandFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new InventoryOnHand;
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

        if ($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        return $query->where('company_id', auth()->user()->company_id)->withTrashed();
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

            $total_purchase = $item->variant->purchase_order_lines()->whereHas('purchase_order', function($q) {  $q->whereNotNull('confirmed_date'); })->count();
            $total_sales = $item->variant->sales_order_lines()->whereHas('sales_order', function($q) {  $q->whereNotNull('confirmed_date'); })->count();
            $total_purchase_return = $item->variant->purchase_order_return_lines()->whereHas('purchase_order_return', function($q) {  $q->whereNotNull('confirmed_date'); })->count();
            $data = array_merge($data, [
                'item_number' => $item->item_number,
                'product_name' => $item->product_name,
                'size' => $item->size,
                'item_unit' => $item->item_unit,
                'ordered_quantity' => $total_purchase,
                'sales_quantity' => $total_sales,
                'purchase_return' => $total_purchase_return,
                'physical_inventory' => number_format($item->physical_inventory, 2, '.', ','),
                'received_quantity' => number_format($item->received_quantity, 2, '.', ','),
                'posted_quantity' => number_format($item->posted_quantity, 2, '.', ','),
                'total_available' => number_format($item->total_available, 2, '.', ','),
                'physical_cost_amount' => number_format($item->physical_cost_amount, 2, '.', ','),
                'financial_cost_amount' => number_format($item->financial_cost_amount, 2, '.', ','),
                // 'client' => $item->parent->client ? $item->parent->client->name : '---',
                // 'product' => $item->parent->product_number,
                // 'product_number' => $item->parent->product_number,
                // 'variant' => $item->name,
                // 'variant_number' => $item->variant_number,
                // 'unit_price' => number_format($item->unit_price, 2, '.', ','),
                // 'status' => $item->renderStatus(),
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
            'showUrl' => $item->renderShowurl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $clients = User::getClients();
        $products = Variant::get();
        $product = null;
        if ($id) {
            $item = InventoryOnHand::withTrashed()->findOrFail($id);
            $item->product = $item->variant->name;
            $item['ordered_quantity'] = $item->variant->purchase_order_lines()->whereHas('purchase_order', function($q) {  $q->whereNotNull('confirmed_date'); })->count();
            $item['sales_quantity'] = $item->variant->sales_order_lines()->whereHas('sales_order', function($q) {  $q->whereNotNull('confirmed_date'); })->count();
            $item['purchase_return'] = $item->variant->purchase_order_return_lines()->whereHas('purchase_order_return', function($q) {  $q->whereNotNull('confirmed_date'); })->count();
            $item = $this->formatView($item);
            // $product = $item->parent;
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'product' => $product,
            'products' => $products,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        // $item->variantUrl = $item->renderShowUrl();
        // $item->on_po = $item->renderPoQuanity();
        // $item->on_so = $item->renderSoQuanity();
        // $item->po_value = $item->renderPoValue();
        // $item->so_value = $item->renderSoValue();
        // $item->on_hand_value = $item->renderOnHandValue();
        
        return $item;
    }
}
