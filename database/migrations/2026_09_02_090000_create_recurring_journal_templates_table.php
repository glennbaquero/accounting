<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringJournalTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_journal_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_id')->index()->nullable();
            $table->string('template_name')->nullable();
            $table->text('description')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('company_id')->nullable();

            $table->string('journal_name')->nullable();
            $table->string('journal_type')->nullable();
            $table->string('account_type')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->string('expense_purpose')->nullable();

            $table->string('frequency')->default('Monthly')->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->datetime('next_run_date')->nullable()->index();
            $table->datetime('last_run_date')->nullable();
            $table->integer('occurrences_limit')->nullable();
            $table->integer('occurrences_generated')->default(0);
            $table->string('status')->default('Active')->nullable()->index();

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
        Schema::dropIfExists('recurring_journal_templates');
    }
}
