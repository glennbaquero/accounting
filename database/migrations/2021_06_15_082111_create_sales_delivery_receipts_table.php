<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesDeliveryReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_delivery_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sales_delivery_receipt_number');
            $table->bigInteger('customer_invoice_id')->unsigned()->index();
            $table->string('customer_account')->index();
            $table->string('invoice_account')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_contact_id');
            $table->date('invoice_date');
            $table->string('invoiced_by')->nullable();
            $table->string('invoice_status')->default('New');
            $table->boolean('invoice_onhold_checkbox')->default(false);
            $table->string('match_variance_type')->nullable();
            $table->string('variance_approved_checkbox')->nullable();
            $table->boolean('posted_invoice_checkbox')->default(false);
            $table->date('posting_date')->nullable();
            $table->bigInteger('posted_by')->nullable();
            $table->boolean('approved_invoice_checkbox')->default(false);
            $table->date('approved_date')->nullable();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->date('payment_due_date');
            $table->date('invoice_payment_release_date')->nullable();
            $table->string('settlement_type')->nullable();
            $table->string('method_of_payment')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('payment_specification')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('tax_exempt_number')->nullable();
            $table->boolean('prices_include_sales_tax_checkbox')->default(false);
            $table->boolean('ignore_calculated_sales_tax_checkbox')->default(false);
            $table->string('cash_discount_code')->nullable();
            $table->decimal('cash_discount_percentage', 9, 2)->default(0);
            $table->string('charges_group')->nullable();
            $table->string('update_quantity_type')->nullable();
            $table->decimal('total_data_quantity', 20, 2)->default(0);
            $table->decimal('total_data_weight', 20, 2)->default(0);
            $table->decimal('total_data_volume', 20, 2)->default(0);
            $table->decimal('total_line_discount', 20, 2)->default(0);
            $table->decimal('subtotal_amount', 20, 2)->default(0);
            $table->decimal('total_discount', 20, 2)->default(0);
            $table->decimal('total_charges', 20, 2)->default(0);
            $table->decimal('total_sales_tax', 20, 2)->default(0);
            $table->decimal('total_round_off', 20, 2)->default(0);
            $table->decimal('total_amount', 20, 2)->default(0);
            $table->decimal('total_cash_discount', 20, 2)->default(0);
            $table->string('posting_profile')->nullable();
            $table->string('accounting_distribution')->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('invoice_account_name')->nullable();
            $table->boolean('is_already_confirmed')->default(false);
            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->string('transaction_type');
            $table->bigInteger('cost_center_id')->unsigned()->index();
            $table->bigInteger('department_id')->unsigned()->index();
            $table->bigInteger('expense_purpose_id')->unsigned()->index();
            $table->string('customer_address')->nullable();
            $table->string('document')->nullable();
            $table->string('document_status')->nullable();
            $table->string('sales_order_return_number')->nullable();
            $table->bigInteger('charge_id')->unsigned()->nullable();
            $table->bigInteger('discount_id')->unsigned()->nullable();
            $table->decimal('total_sales_vat_exclusive', 20, 2)->default(0);
            $table->decimal('less_discount', 20, 2)->default(0);
            $table->decimal('add_charge', 20, 2)->default(0);
            $table->decimal('add_vat', 20, 2)->default(0);
            $table->decimal('total_sales_vat_inclusive', 20, 2)->default(0);
            $table->decimal('less_withholding_tax', 20, 2)->default(0);
            $table->decimal('amount_due', 20, 2)->default(0);
            $table->decimal('vattable_sales', 20, 2)->default(0);
            $table->decimal('vat_exempt_sale', 20, 2)->default(0);
            $table->decimal('zero_rated_sales', 20, 2)->default(0);
            $table->decimal('vat_amount', 20, 2)->default(0);
            $table->decimal('total_amount_due', 20, 2)->default(0);
            $table->decimal('cash_amount', 20, 2)->default(0);
            $table->decimal('check_amount', 20, 2)->default(0);
            $table->decimal('deposit_amount', 20, 2)->default(0);
            $table->decimal('other_amount', 20, 2)->default(0);
            $table->decimal('total_amount_received', 20, 2)->default(0);
            $table->decimal('outstanding', 20, 2)->default(0);
            $table->bigInteger('tax_posting_id')->unsigned()->nullable();
            $table->string('payment_schedule_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_delivery_receipts');
    }
}
