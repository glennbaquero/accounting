<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWriteOffAndNsfPaymentInCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->string('write_off_status')->default('Write Off');
            $table->date('write_off_date')->nullable();
            $table->bigInteger('write_off_issued_by')->unsigned()->nullable();
            $table->text('write_off_description')->nullable();
            $table->date('reverse_write_off_date')->nullable();
            $table->string('nsf_payment_status')->default('NSF Payment');
            $table->date('nsf_payment_date')->nullable();
            $table->bigInteger('nsf_payment_issued_by')->unsigned()->nullable();
            $table->text('nsf_payment_description')->nullable();
            $table->date('reverse_nsf_payment_date')->nullable();
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
