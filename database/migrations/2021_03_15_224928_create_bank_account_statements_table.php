<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_account_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_account_transactions', 'bank_account_transaction_number')) {
                $table->string('bank_account_transaction_number')->nullable();
            }
        });
        Schema::create('bank_account_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');

            $table->string('client_bank_account_number')->nullable();

            $table->string('bank_statement')->nullable();
            $table->string('bank_statement_id')->nullable();
            $table->string('bank_account_transaction_number')->nullable();

            $table->dateTime('bank_statement_issue_date')->nullable();
            $table->dateTime('bank_statement_from_date')->nullable();
            $table->dateTime('bank_statement_to_date')->nullable();
            
            $table->string('prepared_by')->nullable();
            
            $table->boolean('approved')->default(false);
            $table->dateTime('approved_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();

            $table->boolean('canceled')->default(false);
            $table->dateTime('canceled_date')->nullable();
            $table->unsignedInteger('canceled_by')->nullable();

            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();

            $table->string('currency')->nullable();
            $table->decimal('opening_balance', 9, 2)->default(0);
            $table->decimal('ending_balance', 9, 2)->default(0);
            $table->decimal('total_reconciled', 9, 2)->default(0);
            $table->decimal('total_adjustmments', 9, 2)->default(0);
            $table->decimal('total_matched', 9, 2)->default(0);
            $table->boolean('reconciled')->default(false);
            $table->boolean('adjustment')->default(false);

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
        Schema::dropIfExists('bank_account_statements');

        Schema::table('bank_account_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('bank_account_transactions', 'bank_account_transaction_number')) {
                $table->dropColumn('bank_account_transaction_number');
            }
        });
    }
}
