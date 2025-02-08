<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_summary_id')->nullable();
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->string('summary_as_of')->nullable();
            $table->date('issue_date_from')->nullable();
            $table->date('issue_date_to')->nullable();
            $table->bigInteger('prepared_by')->unsigned()->nullable();
            $table->integer('number_sales_order')->default(0);
            $table->integer('number_customer_invoice')->default(0);
            $table->integer('number_overdue_invoice')->default(0);
            $table->decimal('opening_balance', 9, 2)->default(0);
            $table->decimal('invoiced_amount', 9, 2)->default(0);
            $table->decimal('amount_paid', 9, 2)->default(0);
            $table->decimal('balance_due', 9, 2)->default(0);

            $table->boolean('approved')->default(false);
            $table->date('approve_date')->nullable();
            $table->bigInteger('approve_by')->unsigned()->nullable();

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
        Schema::dropIfExists('customer_summaries');
    }
}
