<?php

use Illuminate\Database\Seeder;

use App\Models\Journals\InvoiceApprovalJournal;
use App\Models\JournalLines\InvoiceApprovalJournalVoucher;
use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\PurchaseOrders\PurchaseOrderLine;
use App\Models\Invoices\VendorInvoice;
use App\Models\Invoices\VendorInvoiceLine;

class UpdateInvoiceApprovalJournalData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InvoiceApprovalJournal::truncate();
        InvoiceApprovalJournalVoucher::truncate();

        foreach(VendorInvoice::get() as $invoice) {
        	$invoice->update([
        		'posted_by' => null, 
        		'posting_date'=>null, 
        		'posted_invoice_checkbox' => false
        	]);

        	$invoice->vendor_invoice_lines()->update([
        		'posting_date' => null,
        		'posted_by' => null
        	]);
        }
    }
}
