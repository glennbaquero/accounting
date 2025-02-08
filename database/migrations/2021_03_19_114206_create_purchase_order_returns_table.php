<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('purchase_order_return_number')->unique();
            $table->string('vendor_account');
            $table->string('invoice_account')->nullable();
            $table->datetime('purchase_order_return_date');
            $table->datetime('delivery_date');
            $table->datetime('due_date');
            $table->datetime('approval_status_date')->nullable();
            $table->datetime('confirmed_date')->nullable();
            $table->datetime('accounting_date')->nullable();
            $table->string('vendor_name');
            $table->string('vendor_address');
            $table->string('vendor_contact_id');
            $table->string('confirmed_by')->nullable();
            $table->string('approver')->nullable();
            $table->string('ordered_by');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('posting_profile')->nullable();
            $table->string('accounting_distribution')->nullable();
            $table->string('payment_specification')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('tax_exempt_number')->nullable();
            $table->string('one_time_supplier_checkbox')->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('purchase_order_status')->nullable();
            $table->string('document_status')->nullable();
            $table->string('approval_status')->nullable();
            $table->string('settlement_type')->nullable();
            $table->string('prices_include_sales_tax')->nullable();
            $table->string('delivery_terms_type')->nullable();
            $table->string('mode_of_delivery_type')->nullable();
            $table->string('charges_group')->nullable();
            $table->decimal('cash_discount', 20, 9)->default(0);
            $table->string('line_discount_group')->nullable();
            $table->string('multiline_disc_group')->nullable();
            $table->string('total_discount_group')->nullable();
            $table->decimal('total_discount_percentage', 20, 9)->default(0);
            $table->decimal('update_quantity_type', 20, 9)->default(0);
            $table->decimal('total_data_quantity', 20, 9)->default(0);
            $table->decimal('total_data_volume', 20, 9)->default(0);
            $table->decimal('total_line_discount', 20, 9)->default(0);
            $table->decimal('subtotal_amount', 20, 9)->default(0);
            $table->decimal('total_discount', 20, 9)->default(0);
            $table->decimal('total_charges', 20, 9)->default(0);
            $table->decimal('total_sales_tax', 20, 9)->default(0);
            $table->decimal('total_round_off', 20, 9)->default(0);
            $table->decimal('total_amount', 20, 9)->default(0);
            $table->decimal('total_cash_discount', 20, 9)->default(0);
            $table->string('accouting_distribution')->nullable();
            $table->string('delivery_contact')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('vendor_invoice_number')->nullable();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('terms_of_payment')->unsigned()->index();
            $table->bigInteger('cost_center')->unsigned()->index();
            $table->bigInteger('department')->unsigned()->index();
            $table->bigInteger('expense_purpose')->unsigned()->index();
            $table->string('purchase_order_number')->nullable();
            $table->bigInteger('method_of_payment')->unsigned()->index();
            $table->string('sales_order_number')->nullable();
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
        Schema::dropIfExists('purchase_order_returns');
    }
}
