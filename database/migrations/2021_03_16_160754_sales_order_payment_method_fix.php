<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalesOrderPaymentMethodFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            Schema::table('sales_orders', function (Blueprint $table) {
                if (Schema::hasColumn('sales_orders', 'method_of_payment')) {
                    $table->dropColumn('method_of_payment');
                }
            });
    
            Schema::table('sales_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('sales_orders', 'method_of_payment')) {
                    $table->integer('method_of_payment')->unsigned();
                }
            });
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                if (Schema::hasColumn('purchase_ordersclear', 'method_of_payment')) {
                    $table->dropColumn('method_of_payment');
                }
            });
    
            Schema::table('purchase_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_orders', 'method_of_payment')) {
                    $table->integer('method_of_payment')->unsigned();
                }
            });
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            Schema::table('sales_orders', function (Blueprint $table) {
                if (Schema::hasColumn('sales_orders', 'terms_of_payment')) {
                    $table->dropColumn('terms_of_payment');
                }
            });
    
            Schema::table('sales_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('sales_orders', 'terms_of_payment')) {
                    $table->integer('terms_of_payment')->unsigned();
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
