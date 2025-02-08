<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableVendorInvoiceLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
            $table->integer('unit_price')->nullable();
            $table->integer('unit')->nullable();
            $table->integer('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('unit_price');
            $table->dropColumn('unit');
             $table->integer('amount')->nullable();
        });
    }
}
