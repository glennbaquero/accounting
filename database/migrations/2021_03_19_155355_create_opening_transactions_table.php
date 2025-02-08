<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpeningTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('general_ledger_id')->unsigned()->nullable();
            $table->integer('ledger_journal_id')->unsigned()->nullable();
            $table->integer('ledger_calendar_id')->unsigned()->nullable();
            $table->integer('ledger_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->integer('main_account_id')->unsigned()->nullable();
            $table->integer('main_account_normal_balance')->unsigned()->nullable();
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->string('ledger_journal_status')->nullable();
            $table->decimal('debit', 20, 2)->nullable();
            $table->decimal('credit', 20, 2)->nullable();
            $table->decimal('balance', 20, 2)->nullable();
            $table->boolean('reversed_checkbox')->default(false)->nullable();
            $table->dateTime('reverse_date')->nullable();
            $table->integer('reverse_by')->unsigned()->nullable();
            $table->boolean('adjusted_checkbox')->default(false)->nullable();
            $table->dateTime('adjusted_on')->nullable();
            $table->integer('adjusted_by')->unsigned()->nullable();
            $table->boolean('posted_checkbox')->default(false)->nullable();
            $table->dateTime('posted_on')->nullable();
            $table->integer('posted_by')->unsigned()->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('opening_transactions');
    }
}
