<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV2VendorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->integer('postdated_check_status_id')->unsigned()->index();
            $table->string('check_number');
            $table->timestamp('check_number_issued', 0);
            $table->timestamp('maturity_date', 0);
            $table->timestamp('received_date', 0);
            $table->string('original_check')->nullable();
            $table->string('recepient_name');
            $table->string('cashier');
            $table->string('sales_person');
            $table->string('issuing_bank_branch');
            $table->string('issuing_bank_branch_name');
            $table->decimal('check_amount', 20, 2)->default(0);
            $table->boolean('stop_payment')->default(false);
            $table->boolean('replacement_check')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropColumn('postdated_check_status_id');
            $table->dropColumn('check_number');
            $table->dropColumn('check_number_issued');
            $table->dropColumn('maturity_date');
            $table->dropColumn('received_date');
            $table->dropColumn('original_check');
            $table->dropColumn('recepient_name');
            $table->dropColumn('cashier');
            $table->dropColumn('sales_person');
            $table->dropColumn('issuing_bank_branch');
            $table->dropColumn('issuing_bank_branch_name');
            $table->dropColumn('check_amount');
            $table->dropColumn('stop_payment');
            $table->dropColumn('replacement_check');
        });
    }
}
