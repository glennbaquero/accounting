<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromissoryNoteAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promissory_note_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->integer('purchase_promissory_note_id')->nullable();

            $table->integer('client_id')->nullable();
            $table->string('promissory_note')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->dateTime('due_from')->nullable();
            $table->dateTime('due_to')->nullable();
            $table->decimal('principal_amount', 9, 2)->default(0)->nullable();
            $table->decimal('number_of_times_to_settle', 9, 2)->default(0)->nullable();
            $table->decimal('ammount_to_settle', 9, 2)->default(0)->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('payment_day')->nullable();

            $table->decimal('interest_rate', 9, 2)->default(0)->nullable();
            $table->decimal('interest_amount', 9, 2)->default(0)->nullable();
            $table->string('terms_of_interest')->nullable();
            $table->string('vendor_bank_account')->nullable();
            $table->string('client_bank_account')->nullable();
            $table->string('voucher')->nullable();
            $table->string('stage')->nullable(); // Draw, Redraw, Remit, Settle
            
            $table->string('status')->nullable();
            $table->integer('approved_by_id')->nullable();
            $table->boolean('approved_checkbox')->default(false)->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->integer('posted_by_id')->nullable();
            $table->boolean('posted_checkbox')->default(false)->nullable();
            $table->dateTime('posted_date')->nullable();

            $table->integer('created_by_id')->nullable();
            $table->integer('updated_by_id')->nullable();
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
        Schema::dropIfExists('promissory_note_adjustments');
    }
}
