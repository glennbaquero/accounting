<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDocumentPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_document_postings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_document_postings');
            $table->bigInteger('bank_facility_type_id')->unsigned()->index();
            $table->bigInteger('bank_facility_group_id')->unsigned()->index();
            $table->text('description');
            $table->bigInteger('settle_account_id')->unsigned()->nullable();
            $table->bigInteger('charges_account_id')->unsigned()->nullable();
            $table->bigInteger('margin_account_id')->unsigned()->nullable();
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
        Schema::dropIfExists('bank_document_postings');
    }
}
