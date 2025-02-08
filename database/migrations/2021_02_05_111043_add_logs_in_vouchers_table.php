<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogsInVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
        });

        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
        });

        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
        });

        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
        });

        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
        });

        Schema::table('bill_of_exchange_vouchers', function (Blueprint $table) {
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
        });

        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
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
            $table->dropColumn('logged_by');
            $table->dropColumn('log_message');
            $table->dropColumn('log_in_checkbox');
            $table->dropColumn('log_date');
        });

        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('logged_by');
            $table->dropColumn('log_message');
            $table->dropColumn('log_in_checkbox');
            $table->dropColumn('log_date');
        });

        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('logged_by');
            $table->dropColumn('log_message');
            $table->dropColumn('log_in_checkbox');
            $table->dropColumn('log_date');
        });

        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('logged_by');
            $table->dropColumn('log_message');
            $table->dropColumn('log_in_checkbox');
            $table->dropColumn('log_date');
        });

        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('logged_by');
            $table->dropColumn('log_message');
            $table->dropColumn('log_in_checkbox');
            $table->dropColumn('log_date');
        });

        Schema::table('bill_of_exchange_vouchers', function (Blueprint $table) {
            $table->dropColumn('logged_by');
            $table->dropColumn('log_message');
            $table->dropColumn('log_in_checkbox');
            $table->dropColumn('log_date');
        });

        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->dropColumn('logged_by');
            $table->dropColumn('log_message');
            $table->dropColumn('log_in_checkbox');
            $table->dropColumn('log_date');
        });
    }
}
