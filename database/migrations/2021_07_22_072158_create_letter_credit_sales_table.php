<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterCreditSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_credit_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_document_number');
            $table->date('issue_date');
            $table->bigInteger('issue_by');
            $table->date('application_date');
            $table->date('receipt_date');
            $table->string('amendment_number');
            $table->date('amendment_on');
            $table->bigInteger('amendment_by');
            $table->bigInteger('sales_order_id');
            $table->string('voucher_number')->nullable();

            $table->string('sales_status')->default('created');

            $table->dateTime('close')->nullable();
            $table->boolean('is_close')->default(false);
            $table->bigInteger('close_by')->nullable();
            $table->dateTime('confirmed')->nullable();
            $table->bigInteger('confirmed_by')->nullable();
            $table->boolean('is_confirmed')->default(false);

            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('client_id')->nullable();

            $table->string('created_by')->nullable()->index();
            $table->string('updated_by')->nullable()->index();
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
        Schema::dropIfExists('letter_credit_sales');
    }
}
