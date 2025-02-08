<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsV6CustomerPaymentsTable extends Migration
{
    private static $tableName = 'customer_payments';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'customer_account_id')) {
                $table->dropColumn('customer_account_id');
            }
        });

        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'customer_account_id')) {
                $table->integer('customer_account_id')->nullable()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
