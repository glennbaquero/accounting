<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSalesOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_order_lines', function (Blueprint $table) {
            $table->decimal('quantity')->default(1)->change();
            $table->decimal('price_unit', 20, 2)->default(0)->change();
            $table->decimal('sales_unit', 20, 2)->default(0)->change();
            $table->decimal('line_net_amount', 20, 2)->default(0)->change();

            $table->decimal('unit_price', 20, 2)->default(0)->change();
            $table->decimal('discount', 20, 2)->default(0)->change();
            $table->decimal('discount_percentage', 20, 2)->default(0)->change();
            $table->decimal('multiline_discount', 20, 2)->default(0)->change();
            $table->decimal('multiline_discount_percentage', 20, 2)->default(0)->change();
            $table->decimal('charges_on_sales', 20, 2)->default(0)->change();
            $table->string('unit')->nullable();
            $table->decimal('amount', 20, 2)->default(0);

            $table->date('delivery_date')->nullable()->change();
            $table->date('confirmed_delivery_date')->nullable()->change();

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
        Schema::table('sales_order_lines', function (Blueprint $table) {
            $table->datetime('approved_on')->nullable();
            $table->datetime('rejected_on')->nullable();

            $table->dropColumn('unit');
            $table->dropColumn('amount');
        });
    }
}
