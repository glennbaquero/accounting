<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('budget_id')->index()->nullable();
            $table->string('budget_code')->nullable();
            $table->string('budget_name')->nullable();
            $table->text('description')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('company_id')->nullable();
            $table->string('ledger_id')->nullable();
            $table->string('ledger_code')->nullable();
            $table->string('fiscal_calendar_code')->nullable();
            $table->datetime('budget_year')->nullable();
            $table->string('budget_status')->default('Draft')->nullable();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->nullable()->index();
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
        Schema::dropIfExists('budgets');
    }
}
