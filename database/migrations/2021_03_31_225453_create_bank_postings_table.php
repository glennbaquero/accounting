<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_postings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('client_id')->nullable();
            $table->string('company_id')->nullable()->index();
            $table->string('bank_transaction_posting')->nullable();
            $table->string('description')->nullable();
            $table->string('document')->nullable();
            
            $table->string('bank_posting')->nullable();
            $table->string('bank_posting_code_number')->nullable();

            $table->integer('bank_statement_line_adjustment_id')->nullable();
            $table->integer('cash_register_adjustment_id')->nullable();

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
        Schema::dropIfExists('bank_postings');
    }
}
