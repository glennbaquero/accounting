<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fee_id');
            $table->decimal('fee_amount', 20, 2)->default(0);
            $table->string('remittance_type')->default('None');
            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->bigInteger('client_bank_account_id')->unsigned()->index();
            $table->bigInteger('vendor_payment_method_id')->unsigned()->index();
            $table->bigInteger('customer_payment_method_id')->unsigned()->index();
            $table->string('payment_specification')->nullable();
            $table->date('payment_date');
            $table->date('due_date');
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
        Schema::dropIfExists('payment_fees');
    }
}
