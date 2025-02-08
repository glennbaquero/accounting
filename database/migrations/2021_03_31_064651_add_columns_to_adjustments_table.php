<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashflow_transaction_adjustments', function (Blueprint $table) {
            if (!Schema::hasColumn('cashflow_transaction_adjustments', 'adjustment_checkbox')) {
                $table->boolean('adjustment_checkbox')->default(true);
            }
        });

        Schema::table('bank_account_statement_line_adjustments', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_account_statement_line_adjustments', 'adjustment_checkbox')) {
                $table->boolean('adjustment_checkbox')->default(true);
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
        Schema::table('cashflow_transaction_adjustments', function (Blueprint $table) {
            if (Schema::hasColumn('cashflow_transaction_adjustments', 'adjustment_checkbox')) {
                $table->dropColumn('adjustment_checkbox');
            }
        });

        Schema::table('bank_account_statement_line_adjustments', function (Blueprint $table) {
            if (Schema::hasColumn('bank_account_statement_line_adjustments', 'adjustment_checkbox')) {
                $table->dropColumn('adjustment_checkbox');
            }
        });
    }
}
