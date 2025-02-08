<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVendorInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->date('delivery_date')->nullable();
            $table->string('cash_discount')->nullable();
            $table->string('delivery_contact')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('delivery_term')->nullable();
            $table->string('charge_group')->nullable();
            $table->string('mode_of_delivery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->dropColumn('delivery_date');
            $table->dropColumn('cash_discount');
            $table->dropColumn('delivery_contact');
            $table->dropColumn('delivery_address');
            $table->dropColumn('delivery_term');
            $table->dropColumn('charge_group');
            $table->dropColumn('mode_of_delivery');
        });
    }
}
