<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderReturnJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_return_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('journal_number')->unique();
            $table->string('journal_batch_number');
            $table->string('journal_name_number');
            $table->string('journal_name');
            $table->text('description')->nullable();
            $table->string('journal_status')->default('Open');
            $table->decimal('balance_journal', 20, 2)->default(0);
            $table->decimal('total_debit_journal', 20, 2)->default(0);
            $table->decimal('total_credit_journal', 20, 2)->default(0);
            $table->string('reported_as_ready_by_journal')->nullable();
            $table->string('approved_by_journal')->nullable();
            $table->string('rejected_by_journal')->nullable();
            $table->boolean('posted_checkbox')->default(false);
            $table->date('posted_on')->nullable();
            $table->string('posted_by')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->text('log_message')->nullable();
            $table->boolean('reversing_entry_checkbox')->default(false);
            $table->date('reversing_date')->nullable();
            $table->string('original_journal_number')->nullable();
            $table->boolean('show_user_created_only')->default(false);
            $table->string('journal_type');
            $table->string('account_type');
            $table->string('offset_account')->nullable();
            $table->string('document');
            $table->string('detail_level')->nullable();
            $table->string('posting_layer')->nullable();
            $table->string('number_allocation_at_posting')->nullable();
            $table->string('delete_lines_after_posting')->nullable();
            $table->integer('lines_limit')->default(0);
            $table->boolean('amounts_include_sales_tax')->default(false);
            $table->string('remittance_type')->nullable();
            $table->string('bank_account');
            $table->string('protest_settlements')->nullable();
            $table->string('protest_settled_process')->nullable();
            $table->string('financial_dimensions')->nullable();
            $table->boolean('in_use_checkbox')->default(false);
            $table->string('used_by_user')->nullable();
            $table->string('locked_by_system')->nullable();
            $table->string('private_for_user_group')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->string('expense_purpose')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('rejected_date')->nullable();
            $table->bigInteger('company_id');
            $table->bigInteger('client_id');
            $table->datetime('log_date')->nullable();
            $table->string('logged_by')->nullable();
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
        Schema::dropIfExists('sales_order_return_journals');
    }
}
