<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInBillsOfExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_of_exchanges', function (Blueprint $table) {
            $table->date('issued_date')->nullable();
            $table->integer('boe_due_from')->nullable();
            $table->integer('boe_due_to')->nullable();
            $table->decimal('principal_amount', 20, 2)->default(0);
            $table->string('number_of_time_to_settle')->nullable();
            $table->decimal('amount_to_settle', 20, 2)->default(0);
            $table->string('terms_of_payment')->default('Daily');
            $table->string('payment_day')->nullable();
            $table->decimal('interest_rate', 20, 2)->default(0);
            $table->decimal('interest_amount', 20, 2)->default(0);
            $table->string('terms_of_interest')->nullable();
            $table->bigInteger('customer_bank_account_id')->unsigned()->nullable();
            $table->bigInteger('client_bank_account_id')->unsigned()->nullable();
            $table->string('status')->default('Created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills_of_exchanges', function (Blueprint $table) {
            //
        });
    }
}
