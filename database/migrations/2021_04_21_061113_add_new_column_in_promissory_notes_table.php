<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInPromissoryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promissory_notes', function (Blueprint $table) {
            $table->date('issued_date')->nullable();
            $table->integer('pn_due_from')->nullable();
            $table->integer('pn_due_to')->nullable();
            $table->decimal('principal_amount', 20, 2)->default(0);
            $table->string('number_of_time_to_settle')->nullable();
            $table->decimal('amount_to_settle', 20, 2)->default(0);
            $table->string('terms_of_payment')->default('Daily');
            $table->string('payment_day')->nullable();
            $table->decimal('interest_rate', 20, 2)->default(0);
            $table->decimal('interest_amount', 20, 2)->default(0);
            $table->string('terms_of_interest')->nullable();
            $table->bigInteger('vendor_bank_account_id')->unsigned()->nullable();
            $table->bigInteger('client_bank_account_id')->unsigned()->nullable();
            $table->string('status')->default('Created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promissory_notes', function (Blueprint $table) {
            //
        });
    }
}
