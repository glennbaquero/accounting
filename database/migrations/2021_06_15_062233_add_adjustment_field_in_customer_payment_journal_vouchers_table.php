<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdjustmentFieldInCustomerPaymentJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->boolean('adjusted')->default(false);
            $table->string('adjusted_by')->nullable();
            $table->date('adjusted_on')->nullable();
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
