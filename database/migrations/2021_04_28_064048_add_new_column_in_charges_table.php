<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charges', function (Blueprint $table) {
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
        Schema::table('charges', function (Blueprint $table) {
            //
        });
    }
}
