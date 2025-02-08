<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumns04132021ToCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->string('check_number')->nullable()->change();
            $table->string('postdated_check_status_id')->nullable()->change();
            $table->string('check_amount')->nullable()->change();

            $table->string('vendor_bank_account')->nullable();
            // $table->string('bank_account_type')->nullable();
            $table->string('check_id')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('original_check_number')->nullable();
            $table->string('deposit_status')->nullable();
            $table->string('deposit_slip_number')->nullable();
            $table->decimal('deposit_amount', 9, 2)->default(0)->nullable();
            $table->dateTime('deposit_date')->nullable();
            $table->boolean('deposit_payment_checkbox')->default(false)->nullable();
            $table->string('bank_statement_id')->nullable();
            $table->string('bank_statement_issued_date')->nullable();
            $table->string('bank_posting')->nullable();
            $table->string('bank_reason')->nullable();
            $table->string('bank_reconciliation_id')->nullable();
            $table->dateTime('reconciled_date')->nullable();
            $table->dateTime('adjustment_date')->nullable();

            if (!Schema::hasColumn('customer_payments', 'cashier')) {
                $table->integer('cashier')->nullable()->unsigned();
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
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->dropColumn('vendor_bank_account');
            $table->dropColumn('check_id');
            $table->dropColumn('recipient_name');
            $table->dropColumn('original_check_number');
            $table->dropColumn('deposit_status');
            $table->dropColumn('deposit_slip_number');
            $table->dropColumn('deposit_amount');
            $table->dropColumn('deposit_date');
            $table->dropColumn('deposit_payment_checkbox');
            $table->dropColumn('bank_statement_id');
            $table->dropColumn('bank_statement_issued_date');
            $table->dropColumn('bank_posting');
            $table->dropColumn('bank_reason');
            $table->dropColumn('bank_reconciliation_id');
            $table->dropColumn('reconciled_date');
            $table->dropColumn('adjustment_date');
            $table->dropColumn('cashier');
        });
    }
}
