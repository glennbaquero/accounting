<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_days', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_day');
            $table->string('week_month');
            $table->text('description');
            $table->string('day_of_week')->nullable();
            $table->string('day_of_month')->nullable();
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
        Schema::dropIfExists('payment_days');
    }
}
