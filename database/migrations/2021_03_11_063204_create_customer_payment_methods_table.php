<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_methods', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('client_id');
            $table->string('method_of_payment')->nullable();
            $table->string('method_of_payment_id')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('postdated_check_status')->nullable();
            $table->string('account_type')->nullable();
            $table->string('payment_account')->nullable();
            $table->string('main_account_id')->nullable();
            $table->string('postdated_check_clearing_posting')->nullable();
            $table->string('bank_posting_profile')->nullable();
            $table->string('journal_name')->nullable();

            // $table->string('cost_center')->nullable();
            // $table->string('department')->nullable();
            // $table->string('expense_purpose')->nullable();
            
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

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
        Schema::dropIfExists('customer_payment_methods');
    }
}
