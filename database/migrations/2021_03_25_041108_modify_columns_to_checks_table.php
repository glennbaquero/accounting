<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsToChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checks', function (Blueprint $table) {
            if (!Schema::hasColumn('checks', 'method_of_payment_vendor')) {
                $table->string('method_of_payment_vendor')->nullable();
            }
            if (!Schema::hasColumn('checks', 'method_of_payment_customer')) {
                $table->string('method_of_payment_customer')->nullable();
            }
            if (!Schema::hasColumn('checks', 'vendor_payment_status')) {
                $table->string('vendor_payment_status')->nullable();
            }
            if (!Schema::hasColumn('checks', 'customer_payment_status')) {
                $table->string('customer_payment_status')->nullable();
            }
            if (!Schema::hasColumn('checks', 'customer_payment_id')) {
                $table->string('customer_payment_id')->nullable();
            }
            if (!Schema::hasColumn('checks', 'vendor_payment_id')) {
                $table->string('vendor_payment_id')->nullable();
            }
            if (!Schema::hasColumn('checks', 'reconciled_checkbox')) {
                $table->boolean('reconciled_checkbox')->default(false);
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
        Schema::table('checks', function (Blueprint $table) {
            if (Schema::hasColumn('checks', 'method_of_payment_vendor')) {
                $table->dropColumn('method_of_payment_vendor');
            }
            if (Schema::hasColumn('checks', 'method_of_payment_customer')) {
                $table->dropColumn('method_of_payment_customer');
            }
            if (Schema::hasColumn('checks', 'vendor_payment_status')) {
                $table->dropColumn('vendor_payment_status');
            }
            if (Schema::hasColumn('checks', 'customer_payment_status')) {
                $table->dropColumn('customer_payment_status');
            }
            if (Schema::hasColumn('checks', 'customer_payment_id')) {
                $table->dropColumn('customer_payment_id');
            }
            if (Schema::hasColumn('checks', 'vendor_payment_id')) {
                $table->dropColumn('vendor_payment_id');
            }
            if (Schema::hasColumn('checks', 'reconciled_checkbox')) {
                $table->dropColumn('reconciled_checkbox');
            }
        });
    }
}
