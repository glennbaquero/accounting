<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesDeliveryReceiptLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_delivery_receipt_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sales_delivery_receipt_line_number');
            $table->string('sales_delivery_receipt_number')->index();
            $table->string('sales_order_line_number')->nullable();
            $table->string('sales_order_number')->nullable();
            $table->string('item_number')->nullable();
            $table->string('customer_account')->index();
            $table->string('invoice_account')->nullable();
            $table->bigInteger('line_number');
            $table->string('invoice_line_status')->default('Pending');
            $table->date('delivery_date')->nullable();
            $table->decimal('deliver_remainder', 9, 2)->default(0);
            $table->boolean('close_for_receipt_checkbox')->default(false);
            $table->string('sales_category')->nullable();
            $table->string('item_name')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('price_match')->nullable();
            $table->string('price_total_match')->nullable();
            $table->decimal('invoice_quantity_sales_unit', 9, 2)->default(0);
            $table->decimal('invoice_quantity_inventory_unit', 9, 2)->default(0);
            $table->decimal('sales_unit', 9, 2)->default(0);
            $table->decimal('price_per_unit', 9, 2)->default(0);
            $table->decimal('set_unit_price', 9, 2)->default(0);
            $table->decimal('discount', 9, 2)->default(0);
            $table->decimal('discount_percentage', 9, 2)->default(0);
            $table->decimal('charges_on_sales', 9, 2)->default(0);
            $table->string('sales_tax_group')->nullable();
            $table->string('item_sales_tax_group')->nullable();
            $table->string('subledger_journal')->nullable();
            $table->string('ledger_account')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->datetime('approved_on')->nullable();
            $table->datetime('rejected_on')->nullable();
            $table->text('description')->nullable();
            $table->boolean('posted_invoice_checkbox')->default(false);
            $table->date('posting_date')->nullable();
            $table->bigInteger('posted_by')->nullable();
            $table->string('line_status')->default('Open Order');
            $table->bigInteger('quantity')->default(0);
            $table->decimal('unit_price', 20, 9)->default(0);
            $table->decimal('amount', 20, 9)->default(0);
            $table->bigInteger('cost_center_id')->unsigned()->index();
            $table->bigInteger('department_id')->unsigned()->index();
            $table->json('product')->nullable();
            $table->json('variant')->nullable();
            $table->string('variant_name')->nullable();
            $table->string('variant_number')->nullable();
            $table->decimal('charge_on_purchase', 20, 2)->default(0);
            $table->bigInteger('expense_purpose_id')->unsigned()->index();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('variant_id')->nullable();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->bigInteger('client_id')->unsigned()->index();
            $table->string('customer_name');
            $table->bigInteger('receive_now_quantity')->default(0);
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->bigInteger('procurement_id')->unsigned()->nullable();
            $table->bigInteger('specification_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('service_task')->nullable();
            $table->bigInteger('service_task_details')->nullable();
            $table->bigInteger('rpm_method')->nullable();
            $table->bigInteger('number_of_hours')->nullable();
            $table->bigInteger('charge_id')->unsigned()->nullable();
            $table->bigInteger('discount_id')->unsigned()->nullable();
            $table->decimal('less_discount', 9, 2)->default(0);
            $table->decimal('cash_discount', 9, 2)->default(0);
            $table->decimal('add_charge', 9, 2)->default(0);
            $table->decimal('charge', 9, 2)->default(0);
            $table->decimal('add_fee', 9, 2)->default(0);
            $table->decimal('fee', 9, 2)->default(0);
            $table->decimal('line_amount', 9, 2)->default(0);
            $table->decimal('additional_tax', 9, 2)->default(0);
            $table->decimal('vat_amount', 9, 2)->default(0);
            $table->decimal('line_vat', 9, 2)->default(0);
            $table->decimal('total_sales_vat_inclusive', 9, 2)->default(0);
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
        Schema::dropIfExists('sales_delivery_receipt_lines');
    }
}
