<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountingSegmentInPurchasePromissoryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_promissory_notes', function (Blueprint $table) {
            $table->date('discounted_on')->nullable();
            $table->decimal('discount_rate', 9, 2)->default(0);
            $table->decimal('discount_period', 9, 2)->default(0);
            $table->decimal('discount_amount', 9, 2)->default(0);

            $table->bigInteger('bank_document_id')->unsigned()->nullable();
            $table->bigInteger('bank_facility_type_id')->unsigned()->nullable();
            // $table->bigInteger('interest_calculation_id')->unsigned()->nullable();
            // $table->bigInteger('interest_setup_id')->unsigned()->nullable();

            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('letter_credit_purchase_id')->unsigned()->nullable();
            $table->bigInteger('letter_of_guarantee_id')->unsigned()->nullable();

            $table->string('payment_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_promissory_notes', function (Blueprint $table) {
            //
        });
    }
}
