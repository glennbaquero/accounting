<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('document_status')->nullable()->change();
            $table->string('approval_status')->nullable()->change();
            $table->string('confirmed_date')->nullable()->change();
            $table->string('accounting_date')->nullable()->change();
            $table->string('delivery_contact')->nullable();
            $table->string('delivery_address')->nullable();
            
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
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('delivery_contact');
            $table->dropColumn('delivery_address');
        });
    }
}
