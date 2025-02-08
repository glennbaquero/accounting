<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentScheduleFileToPoSoViCi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('payment_schedule_id')->nullable();
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('payment_schedule_id')->nullable();
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->string('payment_schedule_id')->nullable();
        });

        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->string('payment_schedule_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('payment_schedule_id');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('payment_schedule_id');
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->dropColumn('payment_schedule_id');
        });

        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->dropColumn('payment_schedule_id');
        });
    }
}
