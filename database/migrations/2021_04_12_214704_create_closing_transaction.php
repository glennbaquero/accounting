<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosingTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closing_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('general_ledger_id')->unsigned()->nullable();
            $table->integer('ledger_id')->unsigned()->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->dateTime('closing_date')->nullable();
            $table->dateTime('reverse_date')->nullable();
            $table->integer('reverse_by')->unsigned()->nullable();
            $table->dateTime('adjusted_on')->nullable();
            $table->integer('adjusted_by')->unsigned()->nullable();
            $table->boolean('posted_checkbox')->default(false)->nullable();
            $table->dateTime('posted_on')->nullable();
            $table->integer('posted_by')->unsigned()->nullable();
            $table->dateTime('updated_on')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
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
        Schema::dropIfExists('closing_transactions');
    }
}
