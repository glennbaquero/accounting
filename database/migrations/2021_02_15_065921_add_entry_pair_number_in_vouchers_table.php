<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntryPairNumberInVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->integer('entry_pair_number')->default(0);
        });

        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->integer('entry_pair_number')->default(0);
        });

        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->integer('entry_pair_number')->default(0);
        });

        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->integer('entry_pair_number')->default(0);
        });

        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->integer('entry_pair_number')->default(0);
        });

        Schema::table('bill_of_exchange_vouchers', function (Blueprint $table) {
            $table->integer('entry_pair_number')->default(0);
        });

        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->integer('entry_pair_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->dropColumn('entry_pair_number');
        });

        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('entry_pair_number');
        });

        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('entry_pair_number');
        });

        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('entry_pair_number');
        });

        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('entry_pair_number');
        });

        Schema::table('bill_of_exchange_vouchers', function (Blueprint $table) {
            $table->dropColumn('entry_pair_number');
        });

        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->dropColumn('entry_pair_number');
        });
    }
}
