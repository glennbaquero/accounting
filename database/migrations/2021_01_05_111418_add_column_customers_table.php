<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('method_of_payment')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('payment_specification')->nullable();
            $table->string('tax_exempt_number')->nullable();
            $table->string('payment_type')->nullable();

            $table->string('payment_days')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('use_cash_discount')->nullable();
            $table->string('payment_schedule')->nullable();
            $table->string('bank_account')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_order_lines', function (Blueprint $table) {
            $table->dropColumn('method_of_payment');
            $table->dropColumn('terms_of_payment');
            $table->dropColumn('payment_specification');
            $table->dropColumn('tax_exempt_number');
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_days');
            $table->dropColumn('payment_id');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('use_cash_discount');
            $table->dropColumn('payment_schedule');
            $table->dropColumn('bank_account');
        });
    }
}
