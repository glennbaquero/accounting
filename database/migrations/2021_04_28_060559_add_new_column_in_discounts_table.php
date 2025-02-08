<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->bigInteger('main_account_id')->unsigned()->nullable()->change(); // vendor
            $table->bigInteger('customer_main_account_id')->unsigned()->nullable(); // customer
            $table->bigInteger('vendor_payment_method_id')->unsigned()->nullable()->change();
            $table->bigInteger('customer_payment_method_id')->unsigned()->nullable()->change();
            $table->string('status')->default('Enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
        });
    }
}
