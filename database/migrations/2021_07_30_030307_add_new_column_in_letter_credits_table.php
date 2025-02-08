<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInLetterCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letter_credit_purchases', function (Blueprint $table) {
            $table->string('confirmation_instruction')->default('unconfirmed');
            $table->string('vendor_account')->nullable();
            $table->bigInteger('vendor_bank_account_id')->unsigned()->nullable();
            $table->bigInteger('issuing_bank')->unsigned()->nullable();
            $table->bigInteger('advising_bank')->unsigned()->nullable();
            $table->string('available_with')->nullable();
            $table->bigInteger('bank_document_id')->unsigned()->nullable();
            $table->string('bank_document_type_id')->nullable();
            $table->bigInteger('bank_facility_type_id')->unsigned()->nullable();
            $table->string('bank_facility_agreement_number')->nullable();
            $table->decimal('bank_facility_amount', 9, 2)->default(0);
            $table->string('documentary_credit_type')->nullable();
            $table->string('documentary_credit_nature')->nullable();
            $table->decimal('lc_ic_amount', 9, 2)->default(0);
            $table->decimal('lc_tolerance_amount', 9, 2)->default(0);
            $table->decimal('tolerance_percentage', 9, 2)->default(0);
            $table->string('beneficiary')->nullable();
            $table->string('currency')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('place_of_expiration')->nullable();
            $table->string('partial_shipment')->nullable();
            $table->string('latest_shipment_date')->nullable();
            $table->string('destination_port')->nullable();
            $table->string('description_goods')->nullable();
            $table->string('incoterms')->nullable();
            $table->string('document_required')->nullable();
            $table->string('special_instructions')->nullable();
            $table->string('bank_charges')->nullable();
            $table->string('draft')->nullable();
            $table->decimal('deferred_days', 9, 2)->default(0);
            $table->decimal('period_of_presentation', 9, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('insurance_status')->nullable();
            $table->string('insurance_vendor_number')->nullable();
            $table->string('shipment_number')->nullable();
            $table->date('shipment_date')->nullable();
            $table->date('shipment_date_to')->nullable();
            $table->string('port_loading')->nullable();
            $table->string('port_discharge')->nullable();
            $table->string('purchase_delivery_receipt_date')->nullable();
            $table->string('actual_maturity_date')->nullable();
            $table->decimal('margin_amount', 9, 2)->default(0);
            $table->decimal('allocated', 9, 2)->default(0);
            $table->decimal('settled', 9, 2)->default(0);
            $table->string('shipping_document_status')->nullable();
            $table->string('shipment_status')->nullable();
        });
        Schema::table('letter_credit_sales', function (Blueprint $table) {
            $table->string('confirmation_instruction')->default('unconfirmed');
            $table->string('customer_account')->nullable();
            $table->bigInteger('customer_bank_account_id')->unsigned()->nullable();
            $table->bigInteger('issuing_bank')->unsigned()->nullable();
            $table->bigInteger('advising_bank')->unsigned()->nullable();
            $table->string('available_with')->nullable();
            $table->bigInteger('bank_document_id')->unsigned()->nullable();
            $table->string('bank_document_type_id')->nullable();
            $table->bigInteger('bank_facility_type_id')->unsigned()->nullable();
            $table->string('bank_facility_agreement_number')->nullable();
            $table->decimal('bank_facility_amount', 9, 2)->default(0);
            $table->string('documentary_credit_type')->nullable();
            $table->string('documentary_credit_nature')->nullable();
            $table->string('beneficiary')->nullable();
            $table->decimal('lc_ic_amount', 9, 2)->default(0);
            $table->decimal('lc_tolerance_amount', 9, 2)->default(0);
            $table->decimal('tolerance_percentage', 9, 2)->default(0);
            $table->string('currency')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('place_of_expiration')->nullable();
            $table->string('partial_shipment')->nullable();
            $table->string('latest_shipment_date')->nullable();
            $table->string('destination_port')->nullable();
            $table->string('description_goods')->nullable();
            $table->string('incoterms')->nullable();
            $table->string('document_required')->nullable();
            $table->string('special_instructions')->nullable();
            $table->string('bank_charges')->nullable();
            $table->string('draft')->nullable();
            $table->decimal('deferred_days', 9, 2)->default(0);
            $table->decimal('period_of_presentation', 9, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('insurance_status')->nullable();
            $table->string('insurance_customer_number')->nullable();
            $table->string('shipment_number')->nullable();
            $table->date('shipment_date')->nullable();
            $table->date('shipment_date_to')->nullable();
            $table->string('port_loading')->nullable();
            $table->string('port_discharge')->nullable();
            $table->string('purchase_delivery_receipt_date')->nullable();
            $table->string('actual_maturity_date')->nullable();
            $table->decimal('margin_amount', 9, 2)->default(0);
            $table->decimal('allocated', 9, 2)->default(0);
            $table->decimal('settled', 9, 2)->default(0);
            $table->string('shipping_document_status')->nullable();
            $table->string('shipment_status')->nullable();
            $table->date('notice_date')->nullable();
            $table->string('advising_bank_notice_number')->nullable();
            $table->string('swift_code')->nullable();
            $table->decimal('balance_amount', 9, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letter_credit_purchases', function (Blueprint $table) {
            //
        });
    }
}
