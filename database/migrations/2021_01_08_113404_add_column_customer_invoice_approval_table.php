<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCustomerInvoiceApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->string('customer_invoice_journal_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->dropColumn('customer_invoice_journal_number');
        });
    }
}
