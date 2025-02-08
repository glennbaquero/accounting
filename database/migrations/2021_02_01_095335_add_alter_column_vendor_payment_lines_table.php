<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterColumnVendorPaymentLinesTable extends Migration
{
    private static $tableName = 'vendor_payment_lines';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'purchase_unit')) {
                $table->decimal('purchase_unit', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'cash_discount_percentage')) {
                $table->decimal('cash_discount_percentage', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'set_unit_price')) {
                $table->decimal('set_unit_price', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'discount')) {
                $table->decimal('discount', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'discount_percentage')) {
                $table->decimal('discount_percentage', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'charges_on_purchases')) {
                $table->decimal('charges_on_purchases', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'amount')) {
                $table->decimal('amount', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'price_per_unit')) {
                $table->decimal('price_per_unit', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sales_tax_amount')) {
                $table->decimal('sales_tax_amount', 20, 2)->unsigned()->default(0)->change();
            }

            if (! Schema::hasColumn(static::$tableName, 'sub_total_amount')) {
                $table->decimal('sub_total_amount', 20, 2)->unsigned()->default(0);
            }

            if (! Schema::hasColumn(static::$tableName, 'total_discount')) {
                $table->decimal('total_discount', 20, 2)->unsigned()->default(0);
            }

            if (! Schema::hasColumn(static::$tableName, 'vendor_account')) {
                $table->string('vendor_account')->index();
            }

            if (! Schema::hasColumn(static::$tableName, 'invoice_account')) {
                $table->string('invoice_account')->index();
            }

            if (! Schema::hasColumn(static::$tableName, 'invoice_number')) {
                $table->string('invoice_number')->index();
            }
            
            if (! Schema::hasColumn(static::$tableName, 'purchase_order_number')) {
                $table->string('purchase_order_number')->index();
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
            if (Schema::hasColumn(static::$tableName, 'purchase_unit')) {
                $table->integer('purchase_unit')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'cash_discount_percentage')) {
                $table->integer('cash_discount_percentage')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'set_unit_price')) {
                $table->integer('set_unit_price')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'discount')) {
                $table->integer('discount')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'discount_percentage')) {
                $table->integer('discount_percentage')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'charges_on_purchases')) {
                $table->integer('charges_on_purchases')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'amount')) {
                $table->integer('amount')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'price_per_unit')) {
                $table->integer('price_per_unit')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sales_tax_amount')) {
                $table->integer('sales_tax_amount')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sub_total_amount')) {
                $table->dropColumn('sub_total_amount');
            }

            if (Schema::hasColumn(static::$tableName, 'total_discount')) {
                $table->dropColumn('total_discount');
            }

            if (Schema::hasColumn(static::$tableName, 'vendor_account')) {
                $table->dropColumn('vendor_account');
            }

            if (! Schema::hasColumn(static::$tableName, 'invoice_account')) {
                $table->dropColumn('invoice_account');
            }

            if (! Schema::hasColumn(static::$tableName, 'invoice_number')) {
                $table->dropColumn('invoice_number');
            }
            
            if (! Schema::hasColumn(static::$tableName, 'purchase_order_number')) {
                $table->dropColumn('purchase_order_number');
            }
        });
    }
}
