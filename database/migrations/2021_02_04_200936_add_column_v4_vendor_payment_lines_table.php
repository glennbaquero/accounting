<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV4VendorPaymentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->boolean('approved_payment')->default(false);
            $table->string('approved_by_name')->nullable();
            $table->integer('approved_by_id')->unsigned()->nullable()->index();
            $table->timestamp('approved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->dropColumn('approved_payment');
            $table->dropColumn('approved_by_name');
            $table->dropColumn('approved_by_id');
            $table->dropColumn('approved_date');
        });
    }
}
