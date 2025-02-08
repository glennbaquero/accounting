<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterestCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interest_calculations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();

            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();
            $table->decimal('round_off', 9, 2)->nullable();
            $table->boolean('invoice')->default(false)->nullable();
            $table->boolean('credit_note')->default(false)->nullable();
            $table->boolean('payment')->default(false)->nullable();
            $table->boolean('interest')->default(false)->nullable();

            $table->string('customer_account')->nullable();
            $table->string('invoice_account')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_contact_id')->nullable();
            $table->string('customer_bank_account')->nullable();

            $table->integer('bills_of_exchange_id')->nullable();
            $table->string('posting_profile_from')->nullable();
            $table->integer('customer_posting_profile_id')->nullable();

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
        Schema::dropIfExists('interest_calculations');
    }
}
