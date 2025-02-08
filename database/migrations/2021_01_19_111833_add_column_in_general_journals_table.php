<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInGeneralJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_journals', function (Blueprint $table) {
            $table->string('cost_center');
            $table->string('department');
            $table->string('expense_purpose');
            $table->decimal('balance_journal', 20, 2)->default(0)->change();
            $table->decimal('total_debit_journal', 20, 2)->default(0)->change();
            $table->decimal('total_credit_journal', 20, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_journals', function (Blueprint $table) {
            $table->dropColumn('cost_center');
            $table->dropColumn('department');
            $table->dropColumn('expense_purpose');
        });
    }
}
