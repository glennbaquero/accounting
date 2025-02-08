<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_invoice_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_invoice_line_number')->unique();
            $table->string('vendor_invoice_number')->index();
            $table->string('product_receipt_line_number')->nullable()->index();
            $table->string('product_receipt_number')->nullable()->index();
            $table->string('purchase_order_line_number')->index();
            $table->string('purchase_order_number')->index();
            $table->string('item_number')->index();
            $table->string('vendor_account')->index();
            $table->string('invoice_account')->nullable()->index();

            $table->integer('line_number');
            $table->string('invoice_line_status')->default('Pending'); // Pending, Rejected, Approved
            $table->date('delivery_date')->nullable();
            $table->decimal('deliver_remainder', 9, 2)->default(0);
            $table->boolean('close_for_receipt_checkbox')->default(false);

            $table->string('procurement_category')->nullable();
            $table->string('item_name');
            $table->string('size');
            $table->string('color');
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();

            $table->string('matching_policy')->nullable();
            $table->string('product_receipt_quantity_match')->nullable();
            $table->string('price_match')->nullable();
            $table->string('price_total_match')->nullable();

            $table->decimal('product_receipt_purchase_quantity', 9, 2)->default(0);
            $table->decimal('product_receipt_inventory_quantity', 9, 2)->default(0);

            $table->decimal('invoice_quantity_purchase_unit', 9, 2)->default(0);
            $table->decimal('invoice_quantity_inventory_unit', 9, 2)->default(0);
            $table->decimal('purchase_unit', 9, 2)->default(0);
            $table->decimal('price_per_unit', 9, 2)->default(0);
            $table->decimal('set_unit_price', 9, 2)->default(0);
            $table->decimal('line_net_amount', 9, 2)->default(0);

            $table->decimal('discount', 9, 2)->default(0);
            $table->decimal('discount_percentage', 9, 2)->default(0);
            $table->decimal('multiline_discount', 9, 2)->default(0);
            $table->decimal('multiline_discount_percentage', 9, 2)->default(0);
            $table->decimal('charges_on_purchases', 9, 2)->default(0);

            $table->string('sales_tax_group')->nullable();
            $table->string('item_sales_tax_group')->nullable();
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
        Schema::dropIfExists('vendor_invoice_lines');
    }
}
