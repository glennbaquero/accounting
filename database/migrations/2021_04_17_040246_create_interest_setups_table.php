<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterestSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interest_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();

            $table->string('interest_code')->nullable();
            $table->string('interest_name')->nullable();
            $table->string('description')->nullable();
            $table->string('interest_type')->nullable();
            $table->string('grace_period')->nullable(); // numeric

            $table->dateTime('effective_date')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->string('calculate_interest_every')->nullable(); // Day , Month , Calendar day
            $table->string('interest_earning_debit')->nullable(); // main account
            $table->string('interest_range_by')->nullable(); // None, Amount, Days, Months, Monthly interest %
            
            $table->decimal('interest_amount', 9, 2)->default(0)->nullable();
            $table->decimal('minimum_interest_amount', 9, 2)->default(0)->nullable();
            $table->decimal('maximum_interest_amount', 9, 2)->default(0)->nullable();
            $table->decimal('charge_customer_when_interest_exceeds', 9, 2)->default(0)->nullable();
            $table->decimal('fee_amount', 9, 2)->default(false)->nullable();
            $table->string('fee_account')->nullable(); // main account
            $table->string('sales_tax')->nullable(); // tax table
            $table->string('interest_payment_credit_account')->nullable(); // main account

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interest_setups');
    }
}
