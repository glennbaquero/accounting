<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsOfPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms_of_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('terms_of_payment')->unique();
            $table->integer('payment_method_id')->unsigned()->index();
            $table->integer('months');
            $table->integer('days');
            $table->string('payment_day')->nullable()->index();
            $table->string('payment_schedule')->nullable()->index();
            $table->integer('cutoff_day');
            $table->string('ledger_posting_profile')->nullable()->index();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('terms_of_payments');
    }
}
