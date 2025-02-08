<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('customer_payment_number')->index();
            $table->integer('customer_invoice_id')->unsigned()->index();

            $table->timestamp('issue_date', 0);
            $table->timestamp('payment_release_date', 0);
            $table->timestamp('clearing_date', 0);
            $table->timestamp('due_date', 0);
            $table->timestamp('invoice_date', 0);
            
            $table->string('payee')->nullable();
            $table->text('description')->nullable();
            $table->string('payment_status');

            $table->integer('approved_by_id')->unsigned()->nullable()->index();
            $table->boolean('approved_payment')->default(false);
            $table->string('approved_by_name')->nullable();
            $table->timestamp('approved_date', 0)->nullable();

            $table->integer('posted_by_id')->unsigned()->nullable()->index();
            $table->boolean('posted_payment')->default(false);
            $table->timestamp('posting_date', 0)->nullable();
            $table->string('posted_by_name')->nullable();

            $table->string('sales_tax_group')->nullable();
            $table->string('tax_exempt_group')->nullable();
            $table->boolean('prices_included_sales_tax')->default(false);
            $table->boolean('ignore_calculated_tax')->default(false);
            $table->string('cash_discount_code')->nullable();
            $table->integer('cash_discount')->unsigned()->default(0);
            $table->integer('cash_discount_percentage')->unsigned()->default(0);
            $table->string('charges_group')->nullable();

            $table->integer('customer_account_id')->unsigned()->index();
            $table->string('customer_account')->nullable();
            $table->string('invoice_account')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_contact_id')->nullable();
            
            $table->integer('dimension_value_cost_center_id')->index();
            $table->integer('dimension_value_department_id')->index();
            $table->integer('dimension_value_expense_purpose_id')->index();

            $table->string('posting_profile')->nullable();
            $table->string('accounting_distribution')->nullable();

            $table->integer('created_by')->unsigned()->index();
            $table->integer('updated_by')->unsigned()->nullable()->index();
            $table->integer('settlement_type');
            $table->integer('method_of_payment_id')->unsigned();
            $table->string('payment_specification');
            $table->string('payment_reference');
            $table->integer('bank_transaction_type')->unsigned()->index();
            $table->string('bank_account');

            $table->integer('total_quantity')->unsigned()->default(0);
            $table->integer('total_discount')->unsigned()->default(0);
            $table->integer('total_cash_discount')->unsigned()->default(0);
            $table->integer('total_charges')->unsigned()->default(0);
            $table->integer('total_sales_tax')->unsigned()->default(0);
            $table->integer('total_round_off')->unsigned()->default(0);
            $table->integer('sub_total_amount')->unsigned()->default(0);
            $table->integer('total_amount')->unsigned()->default(0);

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
        Schema::dropIfExists('customer_payments');
    }
}
