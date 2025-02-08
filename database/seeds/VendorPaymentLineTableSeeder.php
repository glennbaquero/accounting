<?php

use App\Models\PurchaseOrders\VendorPaymentLine;
use Illuminate\Database\Seeder;

class VendorPaymentLineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendorPaymentLine::truncate();

        $items = [
            [
                'payment_line_number' => '20210115-1610717385-3145',
                'vendor_payment_id' => 1,
                'vendor_id' => 1,  
                'vendor_invoice_id' => 1,
                'voucher_number' => null,
                'posted_payment' => false,
                'posting_date' => null,
                'posting_by_name' => 'Miko Chu',
                'posting_by_id' => 1,
                'item_sales_tax_group' => null,
                'sales_tax_group' => null,
                'status' => 1,
                'product_id' => 1,
                'item_name' => 'Item Name',
                'procurement_category' => 'Land',
                'size' => 'Size',
                'color' => 'Color',
                'description' => 'Description',
                'quantity' => 5,
                'purchase_unit' => 100,
                'price_per_unit' => 100,
                'set_unit_price' => 140,
                'discount' => 200,
                'discount_percentage' => 12,
                'charges_on_purchases' => 10,
                'amount' => ((5*100)-200)*.88,
                'subledger_journal' => 'Subledger Journal',
                'ledger_account' => 'Ledger Account',
                'dimension_value_cost_center_id' => 1,
                'dimension_value_department_id' => 2,
                'dimension_value_expense_purpose_id' => 3,
                'sales_tax_amount' => 0,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'payment_line_number' => '20210115-1610717391-0498',
                'vendor_payment_id' => 2,
                'vendor_id' => 1,  
                'vendor_invoice_id' => 2,
                'voucher_number' => null,
                'posted_payment' => false,
                'posting_date' => null,
                'posting_by_name' => 'Miko Chu',
                'posting_by_id' => 1,
                'item_sales_tax_group' => null,
                'sales_tax_group' => null,
                'status' => 1,
                'product_id' => 1,
                'item_name' => 'Item Name',
                'procurement_category' => 'Land',
                'size' => 'Size',
                'color' => 'Color',
                'description' => 'Description',
                'quantity' => 5,
                'purchase_unit' => 100,
                'price_per_unit' => 100,
                'set_unit_price' => 140,
                'discount' => 200,
                'discount_percentage' => 12,
                'charges_on_purchases' => 10,
                'amount' => ((5*100)-200)*.88,
                'subledger_journal' => 'Subledger Journal',
                'ledger_account' => 'Ledger Account',
                'dimension_value_cost_center_id' => 1,
                'dimension_value_department_id' => 2,
                'dimension_value_expense_purpose_id' => 3,
                'sales_tax_amount' => 0,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        ];

        foreach($items as $item) {
            VendorPaymentLine::create($item);
        }
    }
}
