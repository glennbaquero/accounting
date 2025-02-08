<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVendorPaymentLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->string('created_by_name')->nullable();
            $table->string('updated_by_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->dropColumn('created_by_name');
            $table->dropColumn('updated_by_name');
        });
    }
}
