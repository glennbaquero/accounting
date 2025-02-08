<?php

use Illuminate\Database\Seeder;

use App\Models\Journals\CustomerInvoiceJournal;
use App\Models\JournalLines\CustomerInvoiceApprovalVoucher;
use App\Models\JournalLines\Voucher;
use App\Models\SalesOrders\SalesOrder;
use App\Models\SalesOrders\SalesOrderLine;
use App\Models\Invoices\CustomerInvoice;
use App\Models\Invoices\CustomerInvoiceLine;

class CustomerInvoiceApprovalJournalSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerInvoiceApprovalVoucher::truncate();
        Voucher::truncate();

        foreach(CustomerInvoice::get() as $invoice) {
        	$invoice->update([
        		'posted_by' => null, 
        		'posting_date'=>null, 
        		'posted_invoice_checkbox' => false
        	]);

        	$invoice->customer_invoice_lines()->update([
        		'posting_date' => null,
        		'posted_by' => null
        	]);
        }
    }
}
