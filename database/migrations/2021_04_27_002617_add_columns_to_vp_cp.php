<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToVpCp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->decimal('total_vattable_sales_vat_exclusive', 9, 2)->default(0)->nullable();
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->decimal('total_vattable_sales_vat_exclusive', 9, 2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropColumn('total_vattable_sales_vat_exclusive');
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->dropColumn('total_vattable_sales_vat_exclusive');
        });
    }
}
