<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add20210416ColumnsToChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dateTime('maturity_date')->nullable();
            $table->string('vendor_invoice_number')->nullable();
            $table->string('customer_invoice_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropColumn('maturity_date');
            $table->dropColumn('vendor_invoice_number');
            $table->dropColumn('customer_invoice_number');
        });
    }
}
