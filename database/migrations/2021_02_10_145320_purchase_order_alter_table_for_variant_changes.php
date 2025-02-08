<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PurchaseOrderAlterTableForVariantChanges extends Migration
{

    private static $tableName = 'purchase_order_lines';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_lines', function (Blueprint $table) {

            if (Schema::hasColumn(static::$tableName, 'color')) {
                $table->dropColumn('color');
            }

            if (Schema::hasColumn(static::$tableName, 'unit')) {
                $table->dropColumn('unit');
            }

            if (Schema::hasColumn(static::$tableName, 'size')) {
                $table->dropColumn('size');
            }
             
            if (Schema::hasColumn(static::$tableName, 'price_unit')) {
                $table->dropColumn('price_unit');
            }

            if (Schema::hasColumn(static::$tableName, 'purchase_unit')) {
                $table->dropColumn('purchase_unit');
            }

            if (Schema::hasColumn(static::$tableName, 'cost_center')) {
                $table->dropColumn('cost_center');
            }

            if (Schema::hasColumn(static::$tableName, 'line_net_amount')) {
                $table->dropColumn('line_net_amount');
            }

            if (Schema::hasColumn(static::$tableName, 'multiline_discount_percentage')) {
                $table->dropColumn('multiline_discount_percentage');
            }

            if (Schema::hasColumn(static::$tableName, 'multiline_discount')) {
                $table->dropColumn('multiline_discount');
            }

            if (Schema::hasColumn(static::$tableName, 'expense_purpose')) {
                $table->dropColumn('expense_purpose');
            }
            
            if (!Schema::hasColumn(static::$tableName, 'cost_center_id')) {
                $table->integer('cost_center_id')->unsigned();
            }

            if (!Schema::hasColumn(static::$tableName, 'department_id')) {
                $table->integer('department_id')->unsigned();
            }

            if (!Schema::hasColumn(static::$tableName, 'product')) {
                $table->json('product');
            }

            if (!Schema::hasColumn(static::$tableName, 'variant')) {
                $table->json('variant');
            }

            if (!Schema::hasColumn(static::$tableName, 'variant_name')) {
                $table->string('variant_name');
            }

            if (!Schema::hasColumn(static::$tableName, 'variant_number')) {
                $table->string('variant_number');
            }

            if (Schema::hasColumn(static::$tableName, 'item_number')) {
                $table->renameColumn('item_number', 'product_number');
            }

            if (Schema::hasColumn(static::$tableName, 'charges_on_purchases')) {
                $table->renameColumn('charges_on_purchases', 'charge_on_purchase');
            }

            if (Schema::hasColumn(static::$tableName, 'charge_on_pruchase')) {
                $table->dropColumn('charge_on_pruchase');
            }

            if (! Schema::hasColumn(static::$tableName, 'expense_purpose_id')) {
                $table->integer('expense_purpose_id')->unsigned();
            }
            
            if (!Schema::hasColumn(static::$tableName, 'product_id')) {
                $table->integer('product_id');
            }

            if (!Schema::hasColumn(static::$tableName, 'variant_id')) {
                $table->integer('variant_id');
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
        Schema::table('purchase_order_lines', function (Blueprint $table) {
            //
        });
    }
}
