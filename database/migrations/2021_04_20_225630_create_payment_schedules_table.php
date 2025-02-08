<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            // Payment Schedule
            $table->integer('client_id')->nullable();
            $table->string('payment_schedule_id')->nullable();
            $table->string('payment_schedule_name')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('schedule_start_date')->nullable();
            $table->dateTime('schedule_end_date')->nullable();
            $table->string('allocation')->nullable(); // Specified, Fixed Quantity, Fixed Amount
            $table->string('payment_per')->nullable(); // Days, Months, Years
            $table->decimal('no_of_payments', 9, 2)->default(0)->nullable();
            $table->decimal('principal_original_amount', 9, 2)->default(0)->nullable();
            $table->decimal('minimum_amount', 9, 2)->default(0)->nullable();
            $table->string('sales_tax_allocation')->nullable(); // Proportionate, First installment, Last installment

            // Installment 
            $table->string('charge_allocation')->nullable(); // Proportionate, First installment, Last installment

            // Related
            $table->string('customer_invoice_number')->nullable();
            $table->integer('bills_exchange_id')->nullable();

            $table->string('payment_schedule_status')->nullable(); // Active, On Hold, Closed

            $table->string('customer_account')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_contact_id')->nullable();

            $table->string('client_bank_account')->nullable();

            // Status
            $table->integer('approved_by')->nullable();
            $table->boolean('approved_checkbox')->default(false)->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

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
        Schema::dropIfExists('payment_schedules');
    }
}
