<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv2CustomerInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            // $table->integer('quantity')->nullable();
            // $table->integer('unit')->nullable();


            $table->decimal('sales_unit', 20, 2)->default(0)->change();
            $table->decimal('line_net_amount', 20, 2)->default(0)->change();
            // $table->string('line_status')->nullable();
            // $table->decimal('amount', 20, 2)->default(0);

            // $table->decimal('unit_price', 20, 2)->default(0);
            $table->decimal('discount', 20, 2)->default(0)->change();
            $table->decimal('discount_percentage', 20, 2)->default(0)->change();
            $table->decimal('multiline_discount', 20, 2)->default(0)->change();
            $table->decimal('multiline_discount_percentage', 20, 2)->default(0)->change();
            $table->decimal('charges_on_sales', 20, 2)->default(0)->change();

            $table->date('delivery_date')->nullable()->change();
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
            $table->dropColumn('quantity');
            $table->dropColumn('unit_price');
            $table->dropColumn('unit');
        });
    }
}
