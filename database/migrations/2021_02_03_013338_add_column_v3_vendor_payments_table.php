<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV3VendorPaymentsTable extends Migration
{
    static $tableName = 'vendor_payments';
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

            if (! Schema::hasColumn(static::$tableName, 'posted_by_name')) {
                $table->string('posted_by_name')->nullable();
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
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');

            if (Schema::hasColumn(static::$tableName, 'posted_by_name')) {
                $table->dropColumn('posted_by_name');
            }
        });
    }
}
