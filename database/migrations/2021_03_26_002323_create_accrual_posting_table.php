<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccrualPostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::dropIfExists('accrual_postings');
        Schema::create('accrual_postings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('accrual_id')->nullable();
            $table->integer('ledger_id')->unsigned()->nullable();
            $table->string('accrual_posting')->nullable();
            $table->string('accrual_status')->nullable();
            $table->integer('prepared_by')->unsigned()->nullable();
            $table->integer('ledger_posting_credit_account_number')->nullable();
            $table->integer('ledger_posting_debit_account_number')->nullable();
            $table->string('ledger_posting_debit')->nullable();
            $table->string('ledger_posting_credit')->nullable();
            $table->string('description')->nullable();
            $table->string('calendar_type')->nullable();
            $table->string('period_frequency')->nullable();
            $table->integer('length')->unsigned()->nullable();
            $table->string('posting_date')->nullable();
            $table->boolean('approved_invoice_checkbox')->default(false);
            $table->dateTime('approved_date')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('updated_on')->nullable();
            $table->dateTime('created_on')->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
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
        Schema::dropIfExists('accrual_postings');
    }
}
