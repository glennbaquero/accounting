<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterestNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interest_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            // General Info
            $table->integer('client_id')->nullable();
            $table->string('interest_note')->nullable();
            $table->dateTime('interest_date')->nullable();
            $table->dateTime('interest_updated_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('days')->nullable();
            $table->string('description')->nullable();
            $table->string('interest_note_voucher')->nullable();

            // Fees
            $table->decimal('fee_note', 9, 2)->default(0)->nullable();
            $table->decimal('fee_write_off_amount', 9, 2)->default(0)->nullable();
            $table->string('fee_adjustment_status')->nullable();
            $table->decimal('total', 9, 2)->default(0)->nullable();
            $table->decimal('sales_tax_amount', 9, 2)->default(0)->nullable();

            // Invoice
            $table->string('invoice_number')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->dateTime('invoice_due_date')->nullable();
            $table->decimal('original_amount', 9, 2)->default(0)->nullable();
            $table->decimal('amount_of_interest', 9, 2)->default(0)->nullable();
            $table->boolean('interest')->default(false)->nullable();
            $table->string('interest_on_transaction_voucher')->nullable();
            $table->string('voucher')->nullable();
            $table->decimal('written_off', 9, 2)->default(0)->nullable();

            // Status
            $table->string('interest_note_status')->nullable();
            $table->string('adjustment_status')->nullable();
            $table->dateTime('canceled')->nullable();
            $table->boolean('block')->default(false)->nullable();
            $table->boolean('posted_checkbox')->default(false)->nullable();
            $table->dateTime('posted_date')->nullable();
            $table->integer('posted_by')->nullable();
            $table->string('posting_profile_from')->nullable();
            $table->integer('customer_posting_profile_id')->nullable();

            // Customer
            $table->string('customer_account')->nullable();
            $table->string('location_id')->nullable();
            $table->string('name_or_description')->nullable();
            $table->string('street')->nullable();
            $table->string('zip_post_code')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('state')->nullable();
            $table->string('country_region')->nullable();
            $table->string('address')->nullable();

            $table->integer('cost_center')->nullable();
            $table->integer('department')->nullable();
            $table->integer('expense_purpose')->nullable();
            $table->string('posting_profile')->nullable();
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
        Schema::dropIfExists('interest_notes');
    }
}
