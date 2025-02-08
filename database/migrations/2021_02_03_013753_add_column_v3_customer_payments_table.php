<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV3CustomerPaymentsTable extends Migration
{
    static $tableName = 'customer_payments';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->index();
            $table->integer('client_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });
    }
}
