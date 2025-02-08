<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DebitCreditRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('main_accounts', 'debit_credit_increase_rule')) {
                $table->string('debit_credit_increase_rule')->nullable();
            }
            if (!Schema::hasColumn('main_accounts', 'debit_credit_decrease_rule')) {
                $table->string('debit_credit_decrease_rule')->nullable();
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
