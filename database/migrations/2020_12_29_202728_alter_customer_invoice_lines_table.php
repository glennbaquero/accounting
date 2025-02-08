<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            $table->datetime('approved_on')->nullable();
            $table->datetime('rejected_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            $table->datetime('approved_on')->nullable();
            $table->datetime('rejected_on')->nullable();
        });
    }
}
