<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterOfGuaranteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_of_guarantees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('letter_of_guarantee_number');
            $table->bigInteger('document_id')->unsigned()->nullable();
            $table->bigInteger('document_type_id')->unsigned()->nullable();
            $table->string('requested_by')->default('Client');
            $table->string('transaction_type')->default('Purchase Order');
            $table->date('received_date');
            $table->date('issue_date');
            $table->date('expiration_date');
            $table->decimal('amount', 9, 2)->default(0);
            $table->string('currency');

            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('client_bank_account_id')->unsigned()->index();

            $table->bigInteger('sales_order_id')->unsigned()->index();

            $table->string('status')->nullable();
            $table->boolean('approved_checkbox')->default(false);
            $table->date('approved_date')->nullable();
            $table->string('approved_by')->nullable();

            $table->boolean('liquidated')->default(false);
            $table->date('liquidated_on')->nullable();
            $table->boolean('extended')->default(false);
            $table->date('extended_on')->nullable();

            $table->bigInteger('purchase_order_id')->unsigned()->index();

            $table->string('bank_facility_status');
            $table->bigInteger('bank_facility_agreement_id')->unsigned()->nullable();
            $table->bigInteger('bank_facility_type_id')->unsigned()->index();
            $table->decimal('margin', 9, 2)->default(0);
            $table->decimal('expense', 9, 2)->default(0);

            $table->text('cancellation_reason')->nullable();
            $table->date('cancellation_date')->nullable();

            $table->text('project_reason')->nullable();
            $table->string('project_number')->nullable();
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
        Schema::dropIfExists('letter_of_guarantees');
    }
}
