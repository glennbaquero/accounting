<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterestAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interest_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            // Header
            $table->integer('client_id')->nullable();
            $table->string('interest_adjustment_id')->nullable();
            $table->dateTime('interest_adjustment_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('customer_account')->nullable();
            $table->string('customer')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->string('transaction_type')->nullable(); // Vendor Invoice, Vendor Payment, Customer Invoice, Customer Payment, Interest Note
            $table->integer('interest_note_id')->nullable();
            $table->decimal('interest_note_amount', 9, 2)->nullable();
            $table->decimal('waived_amount', 9, 2)->nullable();
            $table->decimal('unpaid_balance', 9, 2)->nullable();
            $table->decimal('fee_amount', 9, 2)->nullable();

            // Status
            $table->string('interest_adjustment_status')->nullable(); // New, Pending, Approved, Cancelled
            $table->boolean('approved_checkbox')->dafault(false)->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->boolean('waived_checkbox')->dafault(false)->nullable();
            $table->integer('waived_by')->nullable();
            $table->dateTime('waived_date')->nullable();

            $table->boolean('reinstated_checkbox')->dafault(false)->nullable();
            $table->integer('reinstated_by')->nullable();
            $table->dateTime('reinstated_date')->nullable();

            $table->boolean('reserved_checkbox')->dafault(false)->nullable();
            $table->integer('reserved_by')->nullable();
            $table->dateTime('reserved_date')->nullable();

            $table->boolean('posted_checkbox')->dafault(false)->nullable();
            $table->integer('posted_by')->nullable();
            $table->dateTime('posted_date')->nullable();
            
            // Amounts
            $table->string('voucher')->nullable();
            $table->decimal('write_off_amount', 9, 2)->nullable();
            $table->decimal('fee_write_off_amount', 9, 2)->nullable();

            // FD
            $table->integer('cost_center')->index();
            $table->integer('department');
            $table->integer('expense_purpose');
            $table->integer('posting_profile')->nullable()->index();
            $table->string('document')->nullable();
            $table->string('document_status')->nullable();
            $table->string('accounting_distribution')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

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
        Schema::dropIfExists('interest_adjustments');
    }
}
