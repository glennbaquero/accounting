<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('next_discount_code');
            $table->integer('months');
            $table->integer('days');
            $table->text('description');
            $table->string('net_or_current');
            $table->string('discount_offset_accounts');
            $table->integer('discount_cash')->default('0');
            $table->decimal('discount_percent', 5, 2)->default('0');
            $table->integer('customer_account')->unsigned()->index();
            $table->integer('vendor_account')->unsigned()->index();
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
        Schema::dropIfExists('cash_discounts');
    }
}
