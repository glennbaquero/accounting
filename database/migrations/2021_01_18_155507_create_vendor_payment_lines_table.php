<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPaymentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payment_lines', function (Blueprint $table) {
            $table->increments('id'); // line number +1, +2, +3, ... etc
            $table->string('payment_line_number')->unique();
            $table->integer('vendor_payment_id')->index();
            $table->integer('vendor_id')->index()->nullable(); // vendor account id
            $table->integer('vendor_invoice_id')->index();
            $table->integer('purchase_order_id')->index();
            $table->string('payee')->nullable();
            $table->string('voucher_number')->nullable();
            $table->boolean('posted_payment');
            $table->timestamp('posting_date', 0)->nullable();
            $table->string('posting_by')->nullable();
            $table->string('item_sales_tax_group')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->integer('sales_tax_amount');
            $table->string('line_status');
            $table->integer('product_id')->index();
            $table->string('item_name');
            $table->string('procurement_category');
            $table->string('size');
            $table->string('color');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->integer('purchase_unit');
            $table->integer('price_per_unit');
            $table->integer('set_unit_price');
            $table->integer('discount');
            $table->integer('discount_percentage');
            $table->integer('charges_on_purchases');
            $table->integer('amount');
            $table->string('subledger_journal');
            $table->string('ledger_account');
            $table->integer('dimension_value_cost_center_id')->index();
            $table->integer('dimension_value_department_id')->index();
            $table->integer('dimension_value_expense_purpose_id')->index();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->index()->nullable();
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
        Schema::dropIfExists('vendor_payment_lines');
    }
}