<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductVariationAdjustmentsVendorInvoiceLinesTable extends Migration
{
    private static $tableName = 'vendor_invoice_lines';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
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
                $table->integer('cost_center_id')->unsigned()->index();
            }

            if (!Schema::hasColumn(static::$tableName, 'department_id')) {
                $table->integer('department_id')->unsigned()->index();
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

            if (Schema::hasColumn(static::$tableName, 'charges_on_purchases')) {
                if (! Schema::hasColumn(static::$tableName, 'charge_on_purchase')) {
                    $table->renameColumn('charges_on_purchases', 'charge_on_purchase');
                } else {
                    $table->dropColumn('charges_on_purchases');
                }
            }

            if (Schema::hasColumn(static::$tableName, 'charge_on_pruchase')) {
                $table->dropColumn('charge_on_pruchase');
            }

            if (! Schema::hasColumn(static::$tableName, 'expense_purpose_id')) {
                $table->integer('expense_purpose_id')->unsigned()->index();
            }

            if (!Schema::hasColumn(static::$tableName, 'product_id')) {
                $table->integer('product_id');
            }

            if (!Schema::hasColumn(static::$tableName, 'variant_id')) {
                $table->integer('variant_id');
            }

            if (!Schema::hasColumn(static::$tableName, 'company_id')) {
                $table->integer('company_id')->unsigned()->index();
            }

            if (!Schema::hasColumn(static::$tableName, 'client_id')) {
                $table->integer('client_id')->unsigned()->index();
            }

            if (!Schema::hasColumn(static::$tableName, 'item_number')) {
                $table->string('item_number');
            }

            if (Schema::hasColumn(static::$tableName, 'dimension_value_cost_center_id')) {
                $table->dropColumn('dimension_value_cost_center_id');
            }

            if (Schema::hasColumn(static::$tableName, 'dimension_value_department_id')) {
                $table->dropColumn('dimension_value_department_id');
            }

            if (Schema::hasColumn(static::$tableName, 'dimension_value_expense_purpose_id')) {
                $table->dropColumn('dimension_value_expense_purpose_id');
            }

            if (Schema::hasColumn(static::$tableName, 'product_number')) {
                $table->dropColumn('product_number');
            }

            if (Schema::hasColumn(static::$tableName, 'department')) {
                $table->dropColumn('department');
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
        Schema::table(static::$tableName, function (Blueprint $table) {
        });
    }
}