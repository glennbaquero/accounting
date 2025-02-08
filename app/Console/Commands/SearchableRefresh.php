<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SearchableRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnt:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Refresh all object's searchable array value";

    protected $models = [
        'App\Models\JournalSetups\PaymentMethod',
        'App\Models\ProductInventories\Products\Product',
        'App\Models\Vendors\Vendor',
        'App\Models\JournalSetups\CostCenter',
        'App\Models\JournalSetups\TermsOfPayment',
        'App\Models\PurchaseOrders\PurchaseOrder',
        'App\Models\PurchaseOrders\PurchaseOrderLine',

        'App\Models\Journals\GeneralJournal',
        'App\Models\Journals\CustomerPaymentJournal',
        'App\Models\Journals\CustomerInvoiceJournal',
        'App\Models\Journals\InvoiceApprovalJournal',
        'App\Models\Journals\VendorPaymentJournal',
        'App\Models\SalesOrders\CustomerPayment',

        'App\Models\PurchaseOrders\VendorPayment',
        'App\Models\JournalLines\GeneralJournalVoucher',
        'App\Models\JournalLines\CustomerPaymentJournalVoucher',
        'App\Models\JournalLines\CustomerInvoiceApprovalVoucher',
        'App\Models\JournalLines\InvoiceApprovalJournalVoucher',
        'App\Models\JournalLines\VendorPaymentJournalVoucher',
        'App\Models\Vendors\VendorBankAccount',
        'App\Models\Customers\CustomerBankAccount',
        'App\Models\AdminSetups\ClientBankAccount',

        'App\Models\Checks\Check',
        'App\Models\Deposits\Deposit',
        'App\Models\BankAccountStatements\BankAccountStatement',
        'App\Models\BankAccountStatements\BankAccountStatementLine',
        'App\Models\CashflowTransactions\CashflowTransaction',
        'App\Models\BankPostings\BankPosting',

        'App\Models\GeneralLedgers\GeneralLedger',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(PHP_EOL . "Refreshing searchable array values" . PHP_EOL);

        /* Loop through each php files */
        foreach ($this->models as $key => $model) {

            $this->info('Refreshing ' . $model);

            $model::get()->searchable();
            
        }

        $this->info(PHP_EOL . "Searchable array values successfully refreshed!" . PHP_EOL);        
    }
}
