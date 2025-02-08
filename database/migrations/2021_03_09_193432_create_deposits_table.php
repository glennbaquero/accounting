<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->string('customer_account')->nullable();

            $table->string('client_bank_account_number')->nullable();
            // $table->string('client_bank_account_holder')->nullable();
            // $table->string('client_bank_account_type')->nullable();
            // $table->string('client_bank_name')->nullable();
            // $table->string('client_bank_branch')->nullable();
            // $table->date('client_bank_account_expiry')->nullable();

            $table->string('customer_company')->nullable();
            $table->string('customer_contact')->nullable();

            $table->string('deposit_slip_id')->nullable()->unique();
            $table->string('deposit_slip_number')->nullable(); 
            $table->decimal('deposit_amount', 9, 2)->default(0);
            $table->dateTime('issue_date')->nullable();

            $table->string('bank_posting_profile')->nullable();
            $table->string('method_of_payment_customer')->nullable();
            $table->string('payment_reference')->nullable();
            
            $table->boolean('canceled')->default(false);
            $table->boolean('pending_cancellation')->default(false);
            $table->dateTime('canceled_date')->nullable();
            $table->unsignedInteger('canceled_by')->nullable();

            $table->string('reason_code')->nullable();
            $table->string('reason_comment')->nullable();
            $table->string('description')->nullable();

            $table->dateTime('approved_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();

            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->string('expense_purpose')->nullable();
            $table->string('postdated_check_status')->default('Open')->nullable();
            
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->string('voucher_no')->nullable();

            // Hide first
            // $table->string('vendor_account')->nullable();
            // $table->string('vendor_bank_account')->nullable();
            // $table->string('bank_account_number')->nullable();

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
        Schema::dropIfExists('deposits');
    }
}
