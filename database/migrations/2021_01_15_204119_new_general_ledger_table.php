<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewGeneralLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('general_ledgers');
        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->increments('id');
            

            $table->string('name');
            $table->string('ledger_journal_code');

            $table->integer('ledger_id');
            $table->integer('ledger_calendar_id');

            $table->integer('company_id');
            $table->integer('main_account_id');

            $table->date('period_from');
            $table->date('period_to');

            $table->boolean('matched_voucher_to_gl')->default(false);
            $table->string('ledger_journal_status');

            $table->float('total_debit', 16, 2)->default(0.00);
            $table->float('total_credit', 16, 2)->default(0.00);

            $table->integer('total_journal_lines')->default(0);
            $table->boolean('reversed_checkbox')->default(false);
            $table->datetime('reverse_date')->nullable();
            $table->integer('reverse_by')->nullable();
            $table->boolean('adjusted_checkbox')->default(false);
            $table->datetime('adjusting_date')->nullable();
            $table->integer('adjusted_by')->nullable();
            $table->boolean('posted_checkbox')->default(false);
            $table->datetime('posted_on')->nullable();
            $table->integer('posted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
    
            $table->string('description')->nullable();
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
        Schema::dropIfExists('general_ledgers');
    }
}
