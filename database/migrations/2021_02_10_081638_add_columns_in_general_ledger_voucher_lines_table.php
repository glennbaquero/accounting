<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInGeneralLedgerVoucherLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledger_journal_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ledger_journal_code');
            $table->string('ledger_journal_line_id');
            $table->string('ledger_line_number');
            $table->string('company_name')->nullable();
            $table->integer('company_id');
            $table->integer('client_id');
            $table->string('ledger')->nullable();
            $table->string('ledger_calendar')->nullable();
            $table->string('ledger_journal_name')->nullable();

            $table->string('journal_header_id');
            $table->string('journal_voucher_id');
            $table->string('journal_name');
            $table->string('journal_type');

            $table->string('main_account_code_number');
            $table->string('main_account');
            $table->string('main_account_type');
            $table->string('main_account_category');
            $table->string('main_account_normal_balance');
            $table->datetime('ledger_transaction_date');
            $table->string('cost_center');
            $table->string('department');
            $table->string('expense_purpose');
            $table->string('matched_voucher_to_gl')->nullable();
            $table->string('ledger_journal_line_status')->default('Open');
            $table->decimal('debit_amount', 20, 9)->default(0);
            $table->decimal('credit_amount', 20, 9)->default(0);
            $table->decimal('balance_amount', 20, 9)->default(0);
            $table->boolean('reversed_checkbox')->default(false);
            $table->string('reverse_date')->nullable();
            $table->string('reverse_by')->nullable();
            $table->string('adjusted_checkbox')->nullable();
            $table->string('adjusting_date')->nullable();
            $table->string('adjusted_by')->nullable();

            $table->string('posted_checkbox')->nullable();
            $table->date('posted_on')->nullable();
            $table->string('posted_by')->nullable();
            $table->string('description')->nullable();
            $table->string('posted_voucher')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_ledger_journal_lines', function (Blueprint $table) {
            //
        });
    }
}
