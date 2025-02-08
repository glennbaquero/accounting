<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_payment_number')->index();
            $table->integer('vendor_invoice_id')->index();
            $table->timestamp('issue_date', 0);
            $table->timestamp('payment_release_date', 0);
            $table->timestamp('clearing_date', 0);
            $table->timestamp('due_date', 0);
            $table->timestamp('invoice_date', 0);
            $table->string('payee')->nullable();
            $table->text('description')->nullable();
            $table->string('payment_status');
            $table->boolean('approved_payment')->default(false);
            $table->timestamp('approved_date', 0)->nullable();
            $table->string('approved_by')->nullable();
            $table->boolean('posted_payment')->default(false);
            $table->timestamp('posting_date', 0)->nullable();
            $table->string('posted_by')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('tax_exempt_group')->nullable();
            $table->boolean('prices_included_sales_tax')->default(false);
            $table->boolean('ignore_calculated_tax')->default(false);
            $table->string('cash_discount_code')->nullable();
            $table->integer('cash_discount')->default(0);
            $table->integer('cash_discount_percentage')->default(0);
            $table->string('charges_group')->nullable();
            $table->integer('vendor_account_id')->nullable();
            $table->string('vendor_account')->nullable();
            $table->string('invoice_account')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_address')->nullable();
            $table->string('vendor_contact_id')->nullable();
            $table->integer('dimension_value_cost_center_id')->index();
            $table->integer('dimension_value_department_id')->index();
            $table->integer('dimension_value_expense_purpose_id')->index();
            $table->string('posting_profile')->nullable();
            $table->string('accounting_distribution')->nullable();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('settlement_type')->nullable();
            $table->integer('method_of_payment_id')->index();
            $table->string('payment_specification')->nullable();
            $table->string('payment_reference')->nullable();
            $table->integer('bank_transaction_type')->nullable()->index();
            $table->string('bank_account')->nullable();
            $table->unsignedInteger('total_quantity')->default(0);
            $table->unsignedInteger('total_discount')->default(0);
            $table->unsignedInteger('total_cash_discount')->default(0);
            $table->unsignedInteger('total_charges')->default(0);
            $table->unsignedInteger('total_sales_tax')->default(0);
            $table->unsignedInteger('total_round_off')->default(0);
            $table->unsignedInteger('sub_total_amount')->default(0);
            $table->unsignedInteger('total_amount')->default(0);
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
        Schema::dropIfExists('vendor_payments');
    }
}
