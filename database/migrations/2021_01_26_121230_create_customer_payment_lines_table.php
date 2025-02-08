<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_line_number')->unique();
            $table->integer('customer_payment_id')->index();
            $table->integer('customer_id')->index()->nullable();
            $table->integer('customer_invoice_id')->index();
            $table->string('payee')->nullable();
            $table->string('voucher_number')->nullable();
            $table->boolean('posted_payment')->default(false);
            $table->timestamp('posting_date', 0)->nullable();
            $table->string('posting_by_name')->nullable();
            $table->string('item_sales_tax_group')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->integer('sales_tax_amount')->default(0);
            $table->integer('status')->index();
            $table->integer('product_id')->index();
            $table->string('item_name');
            $table->string('procurement_category');
            $table->string('size');
            $table->string('color');
            $table->text('description')->nullable();
            $table->integer('quantity')->unsigned()->default(0);
            $table->integer('purchase_unit')->unsigned()->default(0);
            $table->integer('price_per_unit')->unsigned()->default(0);
            $table->integer('set_unit_price')->unsigned()->default(0);
            $table->integer('discount')->unsigned()->default(0);
            $table->integer('discount_percentage')->unsigned()->default(0);
            $table->integer('charges_on_purchases')->unsigned()->default(0);
            $table->integer('total_discount')->unsigned()->default(0);
            $table->integer('sub_total_amount')->unsigned()->default(0);
            $table->integer('amount')->unsigned()->default(0);
            $table->string('subledger_journal');
            $table->string('ledger_account');
            $table->integer('dimension_value_cost_center_id')->index();
            $table->integer('dimension_value_department_id')->index();
            $table->integer('dimension_value_expense_purpose_id')->index();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->index()->nullable();
            $table->string('created_by_name')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->string('invoice_account')->index();
            $table->string('invoice_number')->index();
            $table->string('sales_order_number')->index();
            $table->string('customer_account')->nullable()->index();
            $table->integer('posting_by_id')->unsigned()->index()->nullable();
            
            $table->boolean('is_rejected')->default(false);
            $table->integer('rejected_by_id')->unsigned()->index()->nullable();
            $table->string('rejected_by_name')->nullable();
            $table->timestamp('rejected_date', 0)->nullable();
            
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
        Schema::dropIfExists('customer_payment_lines');
    }
}
