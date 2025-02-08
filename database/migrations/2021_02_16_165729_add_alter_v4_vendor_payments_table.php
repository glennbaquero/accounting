<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterV4VendorPaymentsTable extends Migration
{
    private static $tableName = "vendor_payments";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'transaction_type')) {
                $table->string('transaction_type');
            }
            
            if (Schema::hasColumn(static::$tableName, 'vendor_invoice_id')) {
                $table->integer('vendor_invoice_id')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'recepient_name')) {
                $table->string('recepient_name')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'cashier')) {
                $table->string('cashier')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sales_person')) {
                $table->string('sales_person')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'payment_release_date')) {
                $table->dropColumn('payment_release_date');
            }
            
            if (Schema::hasColumn(static::$tableName, 'received_date')) {
                $table->dropColumn('received_date');
            }

            if (Schema::hasColumn(static::$tableName, 'clearing_date')) {
                $table->dropColumn('clearing_date');
            }
        });

        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'payment_release_date')) {
                $table->timestamp('payment_release_date')->nullable();
            }
            
            if (! Schema::hasColumn(static::$tableName, 'received_date')) {
                $table->timestamp('received_date')->nullable();
            }

            if (! Schema::hasColumn(static::$tableName, 'clearing_date')) {
                $table->timestamp('clearing_date')->nullable();
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
            if (Schema::hasColumn(static::$tableName, 'transaction_type')) {
                $table->dropColumn('transaction_type');
            }
            
            if (Schema::hasColumn(static::$tableName, 'vendor_invoice_id')) {
                $table->integer('vendor_invoice_id')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'recepient_name')) {
                $table->string('recepient_name')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'cashier')) {
                $table->string('cashier')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'sales_person')) {
                $table->string('sales_person')->change();
            }

            
            if (Schema::hasColumn(static::$tableName, 'payment_release_date')) {
                $table->dropColumn('payment_release_date');
            }
            
            if (Schema::hasColumn(static::$tableName, 'received_date')) {
                $table->dropColumn('received_date');
            }

            if (Schema::hasColumn(static::$tableName, 'clearing_date')) {
                $table->dropColumn('clearing_date');
            }
        });

        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'payment_release_date')) {
                $table->timestamp('payment_release_date');
            }
            
            if (! Schema::hasColumn(static::$tableName, 'received_date')) {
                $table->timestamp('received_date');
            }

            if (! Schema::hasColumn(static::$tableName, 'clearing_date')) {
                $table->timestamp('clearing_date');
            }
        });
    }
}
