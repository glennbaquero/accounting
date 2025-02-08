<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorPaymentAddMissingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_payment_lines', 'sales_tax_amount')) {
                $table->decimal('sales_tax_amount', 20, 9)->default(0);
            }
            if (!Schema::hasColumn('vendor_payment_lines', 'vendor_payment_id')) {
                $table->integer('vendor_payment_id')->unsigned()->nullabe();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
