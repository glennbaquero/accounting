<?php

use App\Helpers\CodeHelper;
use App\Models\PurchaseOrders\VendorPayment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VendorPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendorPayment::truncate();
        $items = [
            [
                'vendor_invoice_id' => 1,
                'vendor_payment_number' => CodeHelper::generateNumberCode(),
                'issue_date' => Carbon::now(),
                'payment_release_date' => Carbon::now(),
                'clearing_date' => Carbon::now(),
                'due_date' => Carbon::now(),
                'invoice_date' => Carbon::now(),
                'payee' => 'Payee',
                'description' => 'The description',
                'payment_status' => 'Status',
                'approved_payment' => false,
                'approved_date' => null,
                'approved_by' => null,
                'posted_payment' => false,
                'posting_date' => null,
                'posted_by' => null,
                'sales_tax_group' => 'Sales Tax Group',
                'tax_exempt_group' => 'Tax Exempt Group',
                'prices_included_sales_tax' => 0,
                'ignore_calculated_tax' => 0,
                'cash_discount_code' => 'CDC',
                'cash_discount_percentage' => 0,
                'charges_group' => 'Charges Group',
                'vendor_account_id' => 1,
                'vendor_account' => '6001130f9f067',
                'invoice_account' => 'Invoice Account',
                'vendor_name' => 'Vendor Name',
                'vendor_address' => 'Vendor Address',
                'vendor_contact_id' => 'Contact Id',
                'dimension_value_cost_center_id' => 1,
                'dimension_value_department_id' => 3,
                'dimension_value_expense_purpose_id' => 2,
                'posting_profile' => 'Posting Profile',
                'accounting_distribution' => 'Accounting Distribution',
                'created_by' => 1,
                'updated_by' => 1,
                'settlement_type' => 1,
                'method_of_payment_id' => 1,
                'payment_specification' => 'Payment Specification',
                'payment_reference' => 'Payment Reference',
                'bank_transaction_type' => 2,
                'bank_account' => 'Bank Account',
                'total_quantity' => 0,
                'total_discount' => 0,
                'total_cash_discount' => 0,
                'total_charges' => 0,
                'total_sales_tax' => 0,
                'total_round_off' => 0,
                'sub_total_amount' => 0,
                'total_amount' => 0
            ],
            [
                'vendor_invoice_id' => 2,
                'vendor_payment_number' => CodeHelper::generateNumberCode(),
                'issue_date' => Carbon::now(),
                'payment_release_date' => Carbon::now(),
                'clearing_date' => Carbon::now(),
                'due_date' => Carbon::now(),
                'invoice_date' => Carbon::now(),
                'payee' => 'Payee',
                'description' => 'The description',
                'payment_status' => 'Status',
                'approved_payment' => true,
                'approved_date' => Carbon::now(),
                'approved_by' => 1,
                'posted_payment' => true,
                'posting_date' => Carbon::now(),
                'posted_by' => Carbon::now(),
                'sales_tax_group' => 'Sales Tax Group',
                'tax_exempt_group' => 'Tax Exempt Group',
                'prices_included_sales_tax' => 0,
                'ignore_calculated_tax' => 0,
                'cash_discount_code' => 'CDC',
                'cash_discount_percentage' => 0,
                'charges_group' => 'Charges Group',
                'vendor_account_id' => 1,
                'vendor_account' => '6001130f9f067',
                'invoice_account' => 'Invoice Account',
                'vendor_name' => 'Vendor Name',
                'vendor_address' => 'Vendor Address',
                'vendor_contact_id' => 'Contact Id',
                'dimension_value_cost_center_id' => 1,
                'dimension_value_department_id' => 3,
                'dimension_value_expense_purpose_id' => 2,
                'posting_profile' => 'Posting Profile',
                'accounting_distribution' => 'Accounting Distribution',
                'created_by' => 1,
                'updated_by' => 1,
                'settlement_type' => 2,
                'method_of_payment_id' => 1,
                'payment_specification' => 'Payment Specification',
                'payment_reference' => 'Payment Reference',
                'bank_transaction_type' => 1,
                'bank_account' => 'Bank Account',
                'total_quantity' => 0,
                'total_discount' => 0,
                'total_cash_discount' => 0,
                'total_charges' => 0,
                'total_sales_tax' => 0,
                'total_round_off' => 0,
                'sub_total_amount' => 0,
                'total_amount' => 0
            ]
        ];

        foreach($items as $item) {
            VendorPayment::create($item);
        }
    }
}
