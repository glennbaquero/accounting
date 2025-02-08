<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderReturnLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_return_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('return_line_number')->unique();
            $table->string('purchase_order_return_number');
            $table->string('product_number');
            $table->string('vendor_account');
            $table->string('invoice_account')->nullable();
            $table->integer('line_number')->default(0);
            $table->decimal('receive_now_quantity', 20, 9)->default(0);
            $table->boolean('finalized_checkbox')->default(false);
            $table->string('line_status');
            $table->boolean('stopped_checkbox')->default(false);
            $table->string('matching_policy')->nullable();
            $table->string('return_action')->nullable();
            $table->string('procurement_category')->nullable();
            $table->string('product_name')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('quantity', 20, 2)->default(0);
            $table->decimal('unit_price', 20, 2)->default(0);
            $table->decimal('discount', 20, 2)->default(0);
            $table->decimal('discount_percentage', 20, 2)->default(0);
            $table->decimal('charge_on_purchase', 20, 2)->default(0);
            $table->date('delivery_date')->nullable();
            $table->date('confirmed_delivery_date')->nullable();
            $table->string('overdelivery')->nullable();
            $table->string('underdelivery')->nullable();
            $table->string('delivery_type')->nullable();
            $table->string('item_sales_tax_group')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('subledger_journal')->nullable();
            $table->string('ledger_account')->nullable();
            $table->bigInteger('created_by')->unsigned()->index();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->datetime('approved_on')->nullable();
            $table->datetime('rejected_on')->nullable();
            $table->decimal('amount', 20, 2)->default(0);
            $table->bigInteger('cost_center_id');
            $table->bigInteger('department_id');
            $table->text('product');
            $table->text('variant');
            $table->string('variant_name');
            $table->string('variant_number');
            $table->bigInteger('expense_purpose_id')->unsigned()->index();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('variant_id')->unsigned()->index();
            $table->bigInteger('procurement_id')->unsigned()->nullable();
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
        Schema::dropIfExists('purchase_order_return_lines');
    }
}
