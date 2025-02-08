<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentFeeSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_fee_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fee_id');
            $table->bigInteger('customer_payment_method_id')->unsigned()->index();
            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->string('payment_specification');
            $table->string('percentage_amount')->default('Percentage');
            $table->decimal('fee_amount', 20, 2)->default(0);
            $table->decimal('minimum', 20, 2)->default(0);
            $table->decimal('maximum', 20, 2)->default(0);
            $table->date('from_date');
            $table->date('to_date');
            $table->decimal('minimum_fee', 20, 2)->default(0);
            $table->string('tax_account');
            $table->integer('days')->default(1);
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
        Schema::dropIfExists('customer_payment_fee_setups');
    }
}
