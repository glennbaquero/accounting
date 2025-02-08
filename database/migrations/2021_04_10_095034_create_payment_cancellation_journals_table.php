<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentCancellationJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_cancellation_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->integer('company_id')->nullable();

            $table->string('payment_cancellation_journal_number')->nullable();
            $table->string('journal_batch_number')->nullable();
            $table->string('journal_name_number')->nullable()->index();
            $table->string('journal_name')->nullable();
            $table->string('description')->nullable();
            $table->string('journal_status')->nullable();
            $table->decimal('balance_journal', 9, 2)->default(0);
            $table->decimal('total_debit_journal', 9, 2)->default(0);
            $table->decimal('total_credit_journal', 9, 2)->default(0);
            $table->string('reported_as_ready_by_journal')->nullable();
            
            $table->string('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->integer('rejected_by')->nullable();

            $table->boolean('posted_checkbox')->default(false);
            $table->dateTime('posted_on')->nullable();
            $table->integer('posted_by')->nullable();

            $table->boolean('log_in_checkbox')->default(false);
            $table->integer('log_by')->nullable();
            $table->dateTime('log_date')->nullable();
            $table->text('log_message')->nullable();

            $table->boolean('reversing_entry_checkbox')->default(false);
            $table->dateTime('reversing_date')->nullable();

            $table->string('original_journal_number')->nullable();
            $table->boolean('show_user_created_only')->default(false);

            $table->string('journal_type')->nullable();
            $table->string('account_type')->nullable();
            $table->string('offset_account')->nullable();
            $table->string('document')->nullable();

            $table->integer('lines_limit')->default(0);
            $table->boolean('amounts_include_sales_tax')->default(false);
            $table->string('remittance_type')->nullable();

            $table->string('bank_account')->nullable();
            
            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->string('expense_purpose')->nullable();

            $table->boolean('in_use_checkbox')->default(false);
            $table->string('used_by_user')->nullable();

            $table->string('locked_by_system')->nullable();
            $table->string('private_for_user_group')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            
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
        Schema::dropIfExists('payment_cancellation_journals');
    }
}
