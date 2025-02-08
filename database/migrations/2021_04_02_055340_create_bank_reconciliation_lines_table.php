<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankReconciliationLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_reconciliation_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();

            $table->string('bank_reconciliation_line_id')->nullable();
            $table->string('bank_reconciliation_id')->nullable();

            $table->dateTime('posted_date')->nullable();
            $table->unsignedInteger('posted_by')->nullable();
            $table->boolean('posted_checkbox')->default(false);

            $table->dateTime('approved_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->boolean('approved_checkbox')->default(false);

            $table->string('description')->nullable();
            $table->tinyInteger('operation_type')->default(0); // 1: add, 0: less
            $table->string('source')->nullable();

            $table->string('statement_adjustment_id')->nullable();
            $table->string('cash_register_adjustment_id')->nullable();
            $table->string('bank_posting_id')->nullable();

            $table->string('adjustment_name')->nullable();
            $table->decimal('adjustment_amount', 9, 2)->default(0);
            
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

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
        Schema::dropIfExists('bank_reconciliation_lines');
    }
}
