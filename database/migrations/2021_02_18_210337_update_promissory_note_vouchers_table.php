<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePromissoryNoteVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->string('bank_transaction_type')->nullable()->change();
            $table->string('bank_account')->nullable()->change();
            $table->string('method_of_payment')->nullable()->change();
            $table->string('terms_of_payment')->nullable()->change();
            $table->string('payment_specification')->nullable()->change();
            $table->string('payment_deposit_slip')->nullable()->change();
            $table->date('due_date')->nullable()->change();
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
