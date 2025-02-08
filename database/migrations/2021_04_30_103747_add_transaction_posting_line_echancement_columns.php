<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionPostingLineEchancementColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_postings', function (Blueprint $table) {

            if (!Schema::hasColumn('transaction_postings', 'module')) {
                $table->string('module')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'type_of_account')) {
                $table->string('type_of_account')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'debit_account_description')) {
                $table->string('debit_account_description')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'credit_account_description')) {
                $table->string('credit_account_description')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'procurement_posting')) {
                $table->string('procurement_posting')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'method_of_payment_vendor')) {
                $table->string('method_of_payment_vendor')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'method_of_payment_customer')) {
                $table->string('method_of_payment_customer')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'settlement_type')) {
                $table->string('settlement_type')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'bank_posting')) {
                $table->string('bank_posting')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'document_attribute')) {
                $table->string('document_attribute')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'document_values')) {
                $table->string('document_values')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'journal')) {
                $table->string('journal')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'priority')) {
                $table->string('priority')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'match_account_number')) {
                $table->string('match_account_number')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'match_account')) {
                $table->string('match_account')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'account_type')) {
                $table->string('account_type')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'link_account_number')) {
                $table->string('link_account_number')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'link_account')) {
                $table->string('link_account')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'status')) {
                $table->boolean('status')->default(false);
            }

            if (!Schema::hasColumn('transaction_postings', 'main_account')) {
                $table->string('main_account')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'main_account_number')) {
                $table->string('main_account_number')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'main_account_type')) {
                $table->string('main_account_type')->nullable();
            }

            if (!Schema::hasColumn('transaction_postings', 'description')) {
                $table->text('description')->nullable();
            }
            
            if (Schema::hasColumn('transaction_postings', 'account_code')) {
                $table->dropColumn('account_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
