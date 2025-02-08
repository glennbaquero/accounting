<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV2ClientInvoicesTable extends Migration
{
    private static $tableName = 'customer_invoices';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'client_id')) {
                $table->integer('client_id')->unsigned()->index();
            }
            
            if (! Schema::hasColumn(static::$tableName, 'company_id')) {
                $table->integer('company_id')->unsigned()->index();
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
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'client_id')) {
                $table->dropColumn('client_id');
            }
        });

        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'company_id')) {
                $table->dropColumn('company_id');
            }
        });
    }
}
