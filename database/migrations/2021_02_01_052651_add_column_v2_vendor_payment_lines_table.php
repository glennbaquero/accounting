<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV2VendorPaymentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            if (! Schema::hasColumn('vendor_payment_lines', 'status')) {
                $table->integer('status')->index();
            }

            if (Schema::hasColumn('vendor_payment_lines', 'rejected_date')) {
                $table->dateTime('rejected_date', 0)->nullable()->change();
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
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_payment_lines', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('vendor_payment_lines', 'rejected_date')) {
                $table->dateTime('rejected_date', 0)->change();
            }
        });
    }
}
