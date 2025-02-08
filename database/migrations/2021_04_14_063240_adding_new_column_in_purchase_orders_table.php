<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingNewColumnInPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->decimal('total_sales_vat_exclusive', 20, 2)->default(0);
            $table->decimal('less_discount', 20, 2)->default(0);
            $table->decimal('add_charge', 20, 2)->default(0);
            $table->decimal('add_vat', 20, 2)->default(0);
            $table->decimal('total_sales_vat_inclusive', 20, 2)->default(0);
            $table->decimal('less_withholding_tax', 20, 2)->default(0);
            $table->decimal('amount_due', 20, 2)->default(0);
            $table->decimal('vattable_sales', 20, 2)->default(0);
            $table->decimal('vat_exempt_sale', 20, 2)->default(0);
            $table->decimal('zero_rated_sales', 20, 2)->default(0);
            $table->decimal('vat_amount', 20, 2)->default(0);
            $table->decimal('total_amount_due', 20, 2)->default(0);
            $table->decimal('cash_amount', 20, 2)->default(0);
            $table->decimal('check_amount', 20, 2)->default(0);
            $table->decimal('deposit_amount', 20, 2)->default(0);
            $table->decimal('other_amount', 20, 2)->default(0);
            $table->decimal('total_amount_received', 20, 2)->default(0);
            $table->decimal('outstanding', 20, 2)->default(0);
            $table->bigInteger('tax_posting_id')->unsigned()->index();
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
            //
        });
    }
}
