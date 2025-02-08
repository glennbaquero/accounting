<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnVendorPaymentsTable extends Migration
{
    private static $tableName = 'vendor_payments';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'cash_discount')) {
                $table->decimal('cash_discount', 20, 2)->unsigned()->default(0)->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'cash_discount_percentage')) {
                $table->decimal('cash_discount_percentage', 20, 2)->unsigned()->default(0)->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'total_discount')) {
                $table->decimal('total_discount', 20, 2)->unsigned()->default(0)->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'total_cash_discount')) {
                $table->decimal('total_cash_discount', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'total_charges')) {
                $table->decimal('total_charges', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'total_sales_tax')) {
                $table->decimal('total_sales_tax', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sub_total_amount')) {
                $table->decimal('sub_total_amount', 20, 2)->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'total_amount')) {
                $table->decimal('total_amount', 20, 2)->unsigned()->default(0)->change();
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
            if (Schema::hasColumn(static::$tableName, 'cash_discount')) {
                $table->integer('cash_discount')->unsigned()->default(0)->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'cash_discount_percentage')) {
                $table->integer('cash_discount_percentage')->unsigned()->default(0)->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'total_discount')) {
                $table->integer('total_discount')->unsigned()->default(0)->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'total_cash_discount')) {
                $table->integer('total_cash_discount')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'total_charges')) {
                $table->integer('total_charges')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'total_sales_tax')) {
                $table->integer('total_sales_tax')->unsigned()->default(0)->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sub_total_amount')) {
                $table->integer('sub_total_amount')->unsigned()->default(0)->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'total_amount')) {
                $table->integer('total_amount')->unsigned()->default(0)->change();
            }
        });
    }
}
