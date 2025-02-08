<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'method_of_payment_vendor')) {
                $table->string('method_of_payment_vendor')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'method_of_payment_customer')) {
                $table->string('method_of_payment_customer')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'vendor_payment_status')) {
                $table->string('vendor_payment_status')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'customer_payment_status')) {
                $table->string('customer_payment_status')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'customer_payment_id')) {
                $table->string('customer_payment_id')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'vendor_payment_id')) {
                $table->string('vendor_payment_id')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'reconciled_checkbox')) {
                $table->boolean('reconciled_checkbox')->default(false);
            }

            if (!Schema::hasColumn('deposits', 'client_bank_account_number')) {
                $table->string('client_bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'customer_bank_account_number')) {
                $table->string('customer_bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'vendor_bank_account_number')) {
                $table->string('vendor_bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'vendor_company')) {
                $table->string('vendor_company')->nullable();
            }
            if (!Schema::hasColumn('deposits', 'vendor_contact')) {
                $table->string('vendor_contact')->nullable();
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
        Schema::table('deposits', function (Blueprint $table) {
            if (Schema::hasColumn('deposits', 'method_of_payment_vendor')) {
                $table->dropColumn('method_of_payment_vendor');
            }
            if (Schema::hasColumn('deposits', 'method_of_payment_customer')) {
                $table->dropColumn('method_of_payment_customer');
            }
            if (Schema::hasColumn('deposits', 'vendor_payment_status')) {
                $table->dropColumn('vendor_payment_status');
            }
            if (Schema::hasColumn('deposits', 'customer_payment_status')) {
                $table->dropColumn('customer_payment_status');
            }
            if (Schema::hasColumn('deposits', 'customer_payment_id')) {
                $table->dropColumn('customer_payment_id');
            }
            if (Schema::hasColumn('deposits', 'vendor_payment_id')) {
                $table->dropColumn('vendor_payment_id');
            }
            if (Schema::hasColumn('deposits', 'reconciled_checkbox')) {
                $table->dropColumn('reconciled_checkbox');
            }

            if (Schema::hasColumn('deposits', 'client_bank_account_number')) {
                $table->dropColumn('client_bank_account_number');
            }
            if (Schema::hasColumn('deposits', 'customer_bank_account_number')) {
                $table->dropColumn('customer_bank_account_number');
            }
            if (Schema::hasColumn('deposits', 'vendor_bank_account_number')) {
                $table->dropColumn('vendor_bank_account_number');
            }
            if (Schema::hasColumn('deposits', 'vendor_company')) {
                $table->dropColumn('vendor_company');
            }
            if (Schema::hasColumn('deposits', 'vendor_contact')) {
                $table->dropColumn('vendor_contact');
            }
        });
    }
}
