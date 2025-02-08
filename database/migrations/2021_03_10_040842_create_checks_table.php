<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');

            // Client Bank
            $table->string('client_bank_account_number')->nullable();
            // Customer Bank
            $table->string('customer_bank_account_number')->nullable();

            // Check
            $table->string('check_id')->unique();
            $table->string('check_number')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->dateTime('clearing_date')->nullable();
            $table->dateTime('reconciled_date')->nullable();
            $table->string('check_currency')->nullable();
            $table->decimal('check_amount', 9, 2)->default(0);

            // Payment
            $table->string('bank_posting_profile')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('method_of_payment_customer')->nullable();

            // Bank Reason
            $table->string('reason_code')->nullable();
            $table->string('reason_comment')->nullable();
            $table->string('description')->nullable();

            // Status
            $table->string('postdated_check_status')->default('Open')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->boolean('canceled')->default(false);

            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->string('expense_purpose')->nullable();
            
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            // Hide first
            // $table->string('voucher_no')->nullable();
            // $table->string('vendor_account')->nullable();
            // $table->string('vendor_bank_account')->nullable();
            // $table->string('bank_account_number')->nullable();
            // $table->string('client_bank_account_holder')->nullable();
            // $table->string('client_bank_account_type')->nullable();
            // $table->string('client_bank_name')->nullable();
            // $table->string('client_bank_branch')->nullable();
            // $table->date('client_bank_account_expiry')->nullable();

            // $table->string('customer_company')->nullable();
            // $table->string('customer_contact')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checks');
    }
}
