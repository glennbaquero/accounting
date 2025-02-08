<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcurementPostingColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {
            Schema::table('variants', function (Blueprint $table) {
                if (!Schema::hasColumn('variants', 'procurement_id')) {
                    $table->integer('procurement_id')->unsigned()->nullable();
                }
            });
        });

        Schema::table('purchase_order_lines', function (Blueprint $table) {
            Schema::table('purchase_order_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_order_lines', 'procurement_id')) {
                    $table->integer('procurement_id')->unsigned()->nullable();
                }
            });
        });

        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            Schema::table('vendor_invoice_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('vendor_invoice_lines', 'procurement_id')) {
                    $table->integer('procurement_id')->unsigned()->nullable();
                }
            });
        });

        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            Schema::table('vendor_payment_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('vendor_payment_lines', 'procurement_id')) {
                    $table->integer('procurement_id')->unsigned()->nullable();
                }
            });
        });

        Schema::table('sales_order_lines', function (Blueprint $table) {
            Schema::table('sales_order_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('sales_order_lines', 'procurement_id')) {
                    $table->integer('procurement_id')->unsigned()->nullable();
                }
            });
        });

        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            Schema::table('customer_invoice_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('customer_invoice_lines', 'procurement_id')) {
                    $table->integer('procurement_id')->unsigned()->nullable();
                }
            });
        });

        Schema::table('customer_payment_lines', function (Blueprint $table) {
            Schema::table('customer_payment_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('customer_payment_lines', 'procurement_id')) {
                    $table->integer('procurement_id')->unsigned()->nullable();
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
