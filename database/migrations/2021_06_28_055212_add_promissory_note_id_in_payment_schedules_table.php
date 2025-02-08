<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromissoryNoteIdInPaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_schedules', function (Blueprint $table) {
            $table->bigInteger('promissory_note_id')->nullable();
            $table->bigInteger('vendor_invoice_id')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->string('vendor_account')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_schedules', function (Blueprint $table) {
            //
        });
    }
}
