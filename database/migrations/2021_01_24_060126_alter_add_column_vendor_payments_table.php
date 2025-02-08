<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddColumnVendorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            // this is a foreign key but there is an error 
            // when changing approved_by's data type to integer
            // TODO: update to integer when Laravel or MySQL is not throwing error
            //       when changing a string to integer
            $table->string('approved_by')->nullable()->index()->change();

            $table->string('posted_by');
            $table->integer('posted_by_id')->unsigned()->nullable()->index();
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
