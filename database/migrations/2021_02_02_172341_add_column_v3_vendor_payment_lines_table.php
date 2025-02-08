<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV3VendorPaymentLinesTable extends Migration
{
    private static $tableName = 'vendor_payment_lines';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'posting_by_name')) {
                $table->string('posting_by_name')->nullable();
            }

            if (Schema::hasColumn(static::$tableName, 'line_number')) {
                $table->dropColumn('line_number');
            }

            if (! Schema::hasColumn(static::$tableName, 'posting_by_id')) {
                $table->integer('posting_by_id')->unsigned()->nullable()->index();
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
            if (Schema::hasColumn(static::$tableName, 'posting_by_name')) {
                $table->dropColumn('posting_by_name');
            }

            if (! Schema::hasColumn(static::$tableName, 'line_number')) {
                $table->integer('line_number');
            }

            if (Schema::hasColumn(static::$tableName, 'posting_by_id')) {
                $table->dropColumn('posting_by_id');
            }
        });
    }
}
