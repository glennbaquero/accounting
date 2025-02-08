<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInCustomerPaymentJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->string('payment_id')->nullable()->change();
            $table->string('payment_status')->nullable()->change();
            $table->string('postdated_check_status')->default('Open')->change();
            $table->string('cashier')->nullable()->change();
            $table->string('salesperson')->nullable()->change();
            $table->string('issuing_bank_branch')->nullable()->change();
            $table->string('recipient_name')->nullable()->change();
            $table->string('invoice_number')->nullable()->change();
            $table->date('invoice_date')->nullable()->change();
            $table->string('created_by')->change();
            $table->string('updated_by')->nullable()->change();
            $table->string('approved_by_journal')->nullable()->change();
            $table->string('rejected_by_journal')->nullable()->change();
            $table->string('approved_by_id_trans')->nullable()->change();
            $table->string('approved_by_name_trans')->nullable()->change();
            $table->string('posted_checkbox')->nullable()->change();
            $table->string('posting_profile')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            //
        });
    }
}
