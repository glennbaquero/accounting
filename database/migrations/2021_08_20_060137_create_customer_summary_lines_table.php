<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSummaryLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_summary_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('customer_summary_id');
            $table->date('transaction_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('transation_number')->nullable();
            $table->string('transation_type')->nullable();
            $table->bigInteger('method_of_payment_id')->nullable();
            $table->bigInteger('terms_of_payment_id')->nullable();
            $table->string('invoice_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('pdc_status')->nullable();
            $table->string('transaction_status')->nullable();
            $table->decimal('amount_inclusive_tax', 9, 2)->default(0);
            $table->string('payments')->nullable();
            $table->string('outstanding')->nullable();
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
        Schema::dropIfExists('customer_summary_lines');
    }
}
