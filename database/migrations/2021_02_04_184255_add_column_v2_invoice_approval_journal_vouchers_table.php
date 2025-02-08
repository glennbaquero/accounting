<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV2InvoiceApprovalJournalVouchersTable extends Migration
{
    private static $tableName = 'invoice_approval_journal_vouchers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'invoice_journal_number')) {
                // $table->string('invoice_journal_number');
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
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'invoice_journal_number')) {
                // $table->dropColumn('invoice_journal_number');
            }
        });
    }
}
