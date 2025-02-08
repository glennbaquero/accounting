<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fee_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->string('charge_to');
            $table->string('fee_account');
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
        Schema::dropIfExists('customer_payment_fees');
    }
}
