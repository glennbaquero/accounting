<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerPaymentLinesTable extends Migration
{
    private static $tableName = 'customer_payment_lines';

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
    
            if (Schema::hasColumn(static::$tableName, 'sub_total_amount')) {
                $table->decimal('sub_total_amount', 20, 2)->unsigned()->default(0)->change();
            }
    
            if (Schema::hasColumn(static::$tableName, 'total_discount')) {
                $table->decimal('total_discount', 20, 2)->unsigned()->default(0)->change();
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
                $table->integer('sub_total_amount')->unsigned()->default(0)->change();
            }
    
            if (Schema::hasColumn(static::$tableName, 'total_discount')) {
                $table->integer('total_discount')->unsigned()->default(0)->change();
            }
        });
    }
}
