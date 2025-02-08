<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentScheduleLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_schedule_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            $table->integer('client_id')->nullable();
            $table->string('schedule_line_id')->nullable();
            $table->string('payment_schedule_id')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('duration')->nullable(); // numeric
            
            $table->string('principal_amount')->nullable(); // numeric
            $table->string('interest')->nullable(); // numeric
            $table->string('payment')->nullable(); // numeric
            $table->string('balance')->nullable(); // numeric
            $table->string('line_status')->nullable(); // Applied, Printed, Paid, Posted 

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_schedule_lines');
    }
}
