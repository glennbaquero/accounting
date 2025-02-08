<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasePromissoryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_promissory_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stage')->nullable();
            $table->string('promissory_note')->nullable();
            $table->date('issue_date');
            $table->date('due_from');
            $table->date('due_to');
            $table->decimal('principal_amount', 20, 2)->default(0);
            $table->decimal('number_of_times_to_settle', 20, 2)->default(0);
            $table->decimal('ammount_to_settle', 20, 2)->default(0);
            $table->string('terms_of_payment')->default('Daily');
            $table->string('payment_day')->nullable();
            $table->decimal('interest_rate', 20, 2)->default(0);
            $table->decimal('interest_amount', 20, 2)->default(0);
            $table->decimal('terms_of_interest', 20, 2)->default(0);
            $table->bigInteger('vendor_bank_account')->unsigned()->nullable();
            $table->bigInteger('client_bank_account')->unsigned()->nullable();
            $table->string('voucher')->nullable();

            $table->string('status')->nullable();
            $table->integer('approved_by_id')->nullable();
            $table->boolean('approved_checkbox')->default(false);
            $table->dateTime('approved_date')->nullable();
            $table->integer('posted_by_id')->nullable();
            $table->boolean('posted_checkbox')->default(false);
            $table->dateTime('posted_date')->nullable();
            $table->bigInteger('rejected_by_id')->unsigned()->nullable();
            $table->dateTime('rejected_date')->nullable();

            $table->bigInteger('created_by_id')->unsigned()->nullable();
            $table->bigInteger('updated_by_id')->unsigned()->nullable();
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();

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
        Schema::dropIfExists('purchase_promissory_notes');
    }
}
