<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteFinancialDimensionColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'cost_center')) {
                $table->dropColumn('cost_center');
            }
            if (Schema::hasColumn('purchase_orders', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('purchase_orders', 'expense_purpose')) {
                $table->dropColumn('expense_purpose');
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'cost_center')) {
                $table->integer('cost_center')->unsigned();
            }
            if (!Schema::hasColumn('purchase_orders', 'department')) {
                $table->integer('department')->unsigned();
            }
            if (!Schema::hasColumn('purchase_orders', 'expense_purpose')) {
                $table->integer('expense_purpose')->unsigned();
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
        //
    }
}
