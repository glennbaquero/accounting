<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterDropColumnVendorPaymentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->boolean('is_rejected')->default(false);
            $table->integer('rejected_by_id')->nullable()->index();
            $table->string('rejected_by_name')->nullable()->default(null);
            $table->dateTime('rejected_date', 0);

            $table->integer('posting_by_id')->nullable()->index();
            $table->renameColumn('posting_by', 'posting_by_name');
            
            $table->dropColumn('line_number');
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
            $table->dropColumn('is_rejected');
            $table->dropColumn('rejected_by_id');
            $table->dropColumn('rejected_by_name');
            
            $table->dropColumn('rejected_date');
            $table->dropColumn('posting_by_id');

            $table->renameColumn('posting_by_name', 'posting_by');
            $table->integer('line_number');
        });
    }
}
