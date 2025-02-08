<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionPostingHeaderEchancementColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_posting_headers', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_posting_headers', 'effective_date')) {
                $table->dateTime('effective_date')->nullable();
            }

            if (!Schema::hasColumn('transaction_posting_headers', 'expiration_date')) {
                $table->dateTime('expiration_date')->nullable();
            }
    
            if (!Schema::hasColumn('transaction_posting_headers', 'module')) {
                $table->string('module')->nullable();
            }

            if (!Schema::hasColumn('transaction_posting_headers', 'closing_account_segment')) {
                $table->string('closing_account_segment')->nullable();
            }


            if (!Schema::hasColumn('transaction_posting_headers', 'closing_debit_account_code_number')) {
                $table->string('closing_debit_account_code_number')->nullable();
            }

            if (!Schema::hasColumn('transaction_posting_headers', 'closing_credit_account_code_number')) {
                $table->string('closing_credit_account_code_number')->nullable();
            }

            if (!Schema::hasColumn('transaction_posting_headers', 'closing_debit_account')) {
                $table->string('closing_debit_account')->nullable();
            }

            if (!Schema::hasColumn('transaction_posting_headers', 'closing_credit_account')) {
                $table->string('closing_credit_account')->nullable();
            }

            if (!Schema::hasColumn('transaction_posting_headers', 'status')) {
                $table->boolean('status')->default(false);
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
