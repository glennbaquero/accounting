<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnV2CustomerPaymentLinesTable extends Migration
{
    private static $tableName = "customer_payment_lines";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'procurement_category')) {
                $table->string('procurement_category')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'customer_invoice_id')) {
                $table->integer('customer_invoice_id')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'invoice_number')) {
                $table->string('invoice_number')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sales_order_number')) {
                $table->string('sales_order_number')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'subledger_journal')) {
                $table->string('subledger_journal')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'ledger_account')) {
                $table->string('ledger_account')->nullable()->change();
            }


            if (! Schema::hasColumn(static::$tableName, 'item')) {
                $table->json('item')->nullable();
            }


            if (! Schema::hasColumn(static::$tableName, 'variant')) {
                $table->json('variant')->nullable();
            }

            if (! Schema::hasColumn(static::$tableName, 'variant_id')) {
                $table->integer('variant_id')->unsigned()->nullable()->index();
            }


            if (! Schema::hasColumn(static::$tableName, 'client_id')) {
                $table->integer('client_id')->unsigned()->nullable()->index();
            }


            if (! Schema::hasColumn(static::$tableName, 'company_id')) {
                $table->integer('company_id')->unsigned()->nullable()->index();
            }

            if (Schema::hasColumn(static::$tableName, 'item_name')) {
                $table->dropColumn('item_name');                
            }

            if (Schema::hasColumn(static::$tableName, 'size')) {
                $table->dropColumn('size');                
            }

            if (Schema::hasColumn(static::$tableName, 'color')) {
                $table->dropColumn('color');                
            }

            if (Schema::hasColumn(static::$tableName, 'set_unit_price')) {
                $table->dropColumn('set_unit_price');                
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
            if (Schema::hasColumn(static::$tableName, 'procurement_category')) {
                $table->string('procurement_category')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'customer_invoice_id')) {
                $table->integer('customer_invoice_id')->unsigned()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'invoice_number')) {
                $table->string('invoice_number')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sales_order_number')) {
                $table->string('sales_order_number')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'subledger_journal')) {
                $table->string('subledger_journal')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'ledger_account')) {
                $table->string('ledger_account')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'item')) {
                $table->dropColumn('item');
            }

            if (Schema::hasColumn(static::$tableName, 'variant')) {
                $table->dropColumn('variant');
            }

            if (Schema::hasColumn(static::$tableName, 'variant_id')) {
                $table->dropColumn('variant_id');
            }

            if (Schema::hasColumn(static::$tableName, 'client_id')) {
                $table->dropColumn('client_id');
            }

            if (Schema::hasColumn(static::$tableName, 'company_id')) {
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn(static::$tableName, 'item_name')) {
                $table->string('item_name');                
            }

            if (Schema::hasColumn(static::$tableName, 'size')) {
                $table->string('size');                
            }

            if (Schema::hasColumn(static::$tableName, 'color')) {
                $table->string('color');                
            }

            if (Schema::hasColumn(static::$tableName, 'set_unit_price')) {
                $table->decimal('set_unit_price', 20, 2)->default(0);             
            }
        });
    }
}
