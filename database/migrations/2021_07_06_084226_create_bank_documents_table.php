<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('vendor_bank_account_id')->unsigned()->index(); // Advising bank
            $table->string('available_with');
            $table->bigInteger('client_bank_account_id')->unsigned()->index();
            $table->string('bank_facility_agreement_number');
            $table->string('bank_facility_type')->default('Letter Credit');
            $table->string('bank_document_type')->default('Letter Credit');
            $table->decimal('facility_balance', 9, 2)->default(0);

            $table->string('documentary_credit_type');
            $table->string('documentary_credit_nature');
            $table->string('beneficiary');
            $table->decimal('lc_ic_amount', 9, 2)->default(0);
            $table->decimal('lc_tolerance_amount', 9, 2)->default(0);
            $table->integer('tolerance_percentage')->default(0);
            $table->string('tolerance_type');
            $table->string('currency');
            $table->dateTime('expiration_date');
            $table->string('place_of_expiration');

            $table->string('partial_shipment')->default('Not Allowed');
            $table->string('transshipment')->default('Not Allowed');
            $table->string('port_loading');
            $table->date('latest_shipment_date');
            $table->string('destination_port');
            $table->text('description_goods');
            $table->string('incoterms');
            $table->string('document_required');
            $table->string('bank_charges')->default('Bank');
            $table->string('draft')->default('At sight');
            $table->string('preferred_days');
            $table->string('period_of_presentation');
            $table->string('confirmation_instruction');

            $table->string('insurance_number');
            $table->string('insurance_status');
            $table->string('insurance_vendor_number');

            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            
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
        Schema::dropIfExists('bank_documents');
    }
}
