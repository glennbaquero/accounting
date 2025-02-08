<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AtlerColumnV4CustomerPaymentsTable extends Migration
{
    private static $customer_payments = 'customer_payments';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$customer_payments, function (Blueprint $table) {
            if (Schema::hasColumn(static::$customer_payments, 'original_check')) {
                $table->dropColumn('original_check');
            }
        });
        Schema::table(static::$customer_payments, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$customer_payments, 'original_check')) {
                $table->boolean('original_check')->default(0);
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
        Schema::table(static::$customer_payments, function (Blueprint $table) {
            if (Schema::hasColumn(static::$customer_payments, 'original_check')) {
                $table->dropColumn('original_check');
            }
        });
        
        Schema::table(static::$customer_payments, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$customer_payments, 'original_check')) {
                $table->string('original_check')->nullable();
            }
        });
    }
}
