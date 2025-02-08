<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('purchase_order_line_number')->unique();
            $table->string('purchase_order_number')->index();
            $table->string('item_number')->index(); // product
            $table->string('vendor_account')->index();
            $table->string('invoice_account')->nullable()->index();

            $table->integer('line_number');
            $table->decimal('receive_now_quantity', 9, 2)->default(0);
            $table->boolean('finalized_checkbox')->default(false);
            $table->string('line_status');
            $table->boolean('stopped_checkbox')->default(false);
            $table->string('matching_policy')->nullable();
            $table->string('return_action')->nullable();
            
            $table->string('procurement_category')->nullable();
            $table->string('product_name');
            $table->string('size');
            $table->string('color');
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('quantity', 9, 2);
            $table->decimal('price_unit', 9, 2);
            $table->decimal('purchase_unit', 9, 2);
            $table->decimal('line_net_amount', 9, 2);

            
            $table->decimal('unit_price', 9, 2);
            $table->decimal('discount', 9, 2)->default(0);
            $table->decimal('discount_percentage', 9, 2)->default(0);
            $table->decimal('multiline_discount', 9, 2)->default(0);
            $table->decimal('multiline_discount_percentage', 9, 2)->default(0);
            $table->decimal('charges_on_purchases', 9, 2)->default(0);
            
            $table->date('delivery_date');
            $table->date('confirmed_delivery_date');
            $table->string('overdelivery')->nullable();
            $table->string('underdelivery')->nullable();
            $table->string('delivery_type')->nullable();
    
            $table->string('item_sales_tax_group')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('subledger_journal')->nullable()->index();
            $table->string('ledger_account')->nullable()->index();
            $table->string('cost_center')->index();
            $table->string('department');
            $table->string('expense_purpose');

            $table->integer('created_by')->index();
            $table->integer('updated_by')->nullable()->index();

            $table->datetime('approved_on')->nullable();
            $table->datetime('rejected_on')->nullable();

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
        Schema::dropIfExists('purchase_order_lines');
    }
}
