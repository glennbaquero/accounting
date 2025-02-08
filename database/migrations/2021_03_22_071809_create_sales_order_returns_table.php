<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sales_order_return_number')->unique();
            $table->string('customer_account');
            $table->string('invoice_account');
            $table->datetime('sales_order_date');
            $table->datetime('delivery_date');
            $table->datetime('due_date');
            $table->datetime('approval_status_date')->nullable();
            $table->string('confirmed_date')->nullable();
            $table->string('accounting_date')->nullable();
            $table->string('customer_address');
            $table->string('customer_name');
            $table->string('customer_contact_id');
            $table->string('confirmed_by')->nullable();
            $table->string('sold_by')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->string('posting_profile')->nullable();
            $table->string('accounting_distribution')->nullable();
            $table->string('payment_specification')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('tax_exempt_number')->nullable();
            $table->string('sales_type')->nullable();
            $table->string('sales_order_status')->nullable();
            $table->string('document_status')->nullable();
            $table->string('approval_status')->nullable();
            $table->string('settlement_type')->nullable();
            $table->string('delivery_terms')->nullable();
            $table->string('mode_of_delivery')->nullable();
            $table->date('requested_ship_date')->nullable();
            $table->date('requested_receipt_date')->nullable();
            $table->date('confirmed_ship_date')->nullable();
            $table->date('confirmed_receipt_date')->nullable();
            $table->boolean('expedited_shipment_checkbox')->default(false);
            $table->string('commission_group')->nullable();
            $table->string('sales_representative')->nullable();
            $table->string('sales_unit')->nullable();
            $table->string('prices_include_sales_tax')->nullable();
            $table->decimal('price_group',20, 2)->default(0);
            $table->string('charges_group')->nullable();
            $table->decimal('cash_discount', 20, 2)->default(0);
            $table->decimal('discount_percentage', 20, 2)->default(0);
            $table->string('line_discount_group')->nullable();
            $table->string('multiline_disc_group')->nullable();
            $table->string('total_discount_group')->nullable();
            $table->decimal('total_discount_percentage', 20, 2)->default(0);
            $table->string('update_quantity_type')->nullable();
            $table->decimal('total_data_quantity', 20, 2)->default(0);
            $table->decimal('total_data_volume', 20, 2)->default(0);
            $table->decimal('total_line_discount', 20, 2)->default(0);
            $table->decimal('subtotal_amount', 20, 2)->default(0);
            $table->decimal('total_discount', 20, 2)->default(0);
            $table->decimal('total_charges', 20, 2)->default(0);
            $table->decimal('total_sales_tax', 20, 2)->default(0);
            $table->decimal('total_round_off', 20, 2)->default(0);
            $table->decimal('total_amount', 20, 2)->default(0);
            $table->decimal('total_cash_discount', 20, 2)->default(0);
            $table->string('approver')->nullable();
            $table->string('delivery_contact')->nullable();
            $table->string('delivery_address')->nullable();
            $table->datetime('approved_on')->nullable();
            $table->datetime('rejected_on')->nullable();
            $table->string('customer_invoice_number')->nullable();
            $table->bigInteger('company_id');
            $table->bigInteger('client_id');
            $table->bigInteger('cost_center_id');
            $table->bigInteger('department_id');
            $table->bigInteger('expense_purpose_id');
            $table->bigInteger('method_of_payment')->nullable();
            $table->bigInteger('terms_of_payment')->nullable();
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
        Schema::dropIfExists('sales_order_returns');
    }
}
