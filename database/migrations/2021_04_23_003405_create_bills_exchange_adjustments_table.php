<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsExchangeAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_exchange_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->integer('bills_exchange_id')->nullable();

            $table->integer('client_id')->nullable();
            $table->string('bills_of_exchange')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->dateTime('due_from')->nullable();
            $table->dateTime('due_to')->nullable();
            $table->decimal('principal_amount', 9, 2)->default(0)->nullable();
            $table->decimal('number_of_times_to_settle', 9, 2)->default(0)->nullable();
            $table->decimal('ammount_to_settle', 9, 2)->default(0)->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('payment_day')->nullable();

            $table->decimal('interest_rate', 9, 2)->default(0)->nullable();
            $table->decimal('interest_amount', 9, 2)->default(0)->nullable();
            $table->string('terms_of_interest')->nullable();
            $table->string('customer_bank_account')->nullable();
            $table->string('client_bank_account')->nullable();
            $table->string('voucher')->nullable();
            $table->string('bills_of_exchange_stage')->nullable(); // Draw, Redraw, Remit, Settle
            
            $table->string('status')->nullable();
            $table->integer('approved_by')->nullable();
            $table->boolean('approved_checkbox')->default(false)->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->integer('posted_by')->nullable();
            $table->boolean('posted_checkbox')->default(false)->nullable();
            $table->dateTime('posted_date')->nullable();

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
        Schema::dropIfExists('bills_exchange_adjustments');
    }
}
