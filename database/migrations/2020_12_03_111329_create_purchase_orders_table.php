<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('purchase_order_number')->unique();
            $table->string('vendor_account')->index();
            $table->string('invoice_account')->nullable()->index();

            $table->datetime('purchase_order_date');
            $table->datetime('delivery_date');
            $table->datetime('due_date');
            $table->datetime('approval_status_date')->nullable();
            $table->datetime('confirmed_date');
            $table->datetime('accounting_date');

            $table->string('vendor_name');
            $table->string('vendor_address');
            $table->string('vendor_contact_id');
            $table->string('confirmed_by')->nullable()->index();
            $table->string('approver')->nullable()->index();
            $table->string('ordered_by')->index();
            $table->string('created_by')->nullable()->index();
            $table->string('updated_by')->nullable()->index();

            $table->string('cost_center')->index();
            $table->string('department');
            $table->string('expense_purpose');
            $table->string('posting_profile')->nullable()->index();
            $table->string('accounting_distribution')->nullable();

            $table->string('method_of_payment')->index();
            $table->string('terms_of_payment')->index();
            $table->string('payment_specification')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('tax_exempt_number')->nullable();

            $table->string('one_time_supplier_checkbox')->nullable();
            $table->string('purchase_type');
            $table->string('purchase_order_status');
            $table->string('document_status');
            $table->string('approval_status');
            $table->string('settlement_type')->nullable();

            $table->string('prices_include_sales_tax')->nullable();
            $table->string('delivery_terms_type')->nullable()->index();
            $table->string('mode_of_delivery_type')->nullable();
            $table->string('charges_group')->nullable();
            $table->decimal('cash_discount', 9, 2)->nullable();
            $table->string('line_discount_group')->nullable();
            $table->string('multiline_disc_group')->nullable();
            $table->string('total_discount_group')->nullable();
            $table->decimal('total_discount_percentage', 9, 2)->nullable();
            
            $table->string('update_quantity_type')->nullable();
            $table->decimal('total_data_quantity', 9, 2)->nullable();
            $table->decimal('total_data_volume', 9, 2)->nullable();
            $table->decimal('total_line_discount', 9, 2)->nullable();
            $table->decimal('subtotal_amount', 9, 2)->nullable();
            $table->decimal('total_discount', 9, 2)->nullable();
            $table->decimal('total_charges', 9, 2)->nullable();
            $table->decimal('total_sales_tax', 9, 2)->nullable();
            $table->decimal('total_round_off', 9, 2)->nullable();
            $table->decimal('total_amount', 9, 2)->nullable();
            $table->decimal('total_cash_discount', 9, 2)->nullable();

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
        Schema::dropIfExists('purchase_orders');
    }
}
