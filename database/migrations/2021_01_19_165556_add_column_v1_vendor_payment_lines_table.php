<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV1VendorPaymentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            // $table->string('invoice_account')->index()->change();
            // $table->string('invoice_number')->index()->change();
            // $table->string('purchase_order_number')->index()->change();
            // $table->integer('status')->index()->change();
            // $table->string('vendor_account')->index()->change();
            // $table->integer('vendor_payment_id')->index()->change();
            

            if (!Schema::hasColumn('financial_dimensions', 'use_value_from')){
                $table->integer('sales_tax_amount');
            }
            if (!Schema::hasColumn('financial_dimensions', 'use_value_from')){
                $table->integer('line_number');
            }
            if (!Schema::hasColumn('financial_dimensions', 'use_value_from')){
                $table->integer('total_discount');
            }
            if (!Schema::hasColumn('financial_dimensions', 'use_value_from')){
                $table->integer('sub_total_amount');
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
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->dropColumn('invoice_account');
            $table->dropColumn('invoice_number');
            $table->dropColumn('purchase_order_number');
            $table->dropColumn('status');
            $table->dropColumn('vendor_account');
            $table->dropColumn('vendor_payment_id');
            $table->dropColumn('sales_tax_amount');
            $table->dropColumn('line_number');
            $table->dropColumn('total_discount');
            $table->dropColumn('sub_total_amount');
        });
    }
}
