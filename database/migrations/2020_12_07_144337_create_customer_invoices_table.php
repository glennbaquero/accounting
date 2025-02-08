<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_invoice_number')->unique();
            $table->string('sales_order_number')->index();
            $table->string('customer_account')->index();
            $table->string('invoice_account')->nullable()->index();
            $table->string('payment_id')->nullable()->index();

            $table->string('customer_name');            
            $table->string('customer_contact_id');

            $table->datetime('invoice_date');              
            $table->string('invoiced_by');  
            $table->string('invoice_status')->default('New');  //New or Pending or Posted
            $table->boolean('invoice_onhold_checkbox')->default(false);          
            $table->string('match_variance_type')->nullable();
            $table->string('variance_approved_checkbox')->nullable();

            $table->boolean('posted_invoice_checkbox')->default(false); 
            $table->date('posting_date')->nullable();               
            $table->integer('posted_by')->nullable()->index();   
             
            $table->boolean('approved_invoice_checkbox')->default(false); 
            $table->date('approved_date')->nullable();               
            $table->integer('approved_by')->nullable()->index();  

            $table->date('payment_due_date');               
            $table->date('invoice_payment_release_date');               
            $table->string('settlement_type')->nullable();               
            $table->string('method_of_payment')->nullable()->index();               
            $table->string('terms_of_payment')->nullable()->index();               
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
            $table->decimal('total_data_quantity', 9, 2)->default(0);
            $table->decimal('total_data_weight', 9, 2)->default(0);
            $table->decimal('total_data_volume', 9, 2)->default(0);
            $table->decimal('total_line_discount', 9, 2)->default(0);
            $table->decimal('subtotal_amount', 9, 2)->default(0);
            $table->decimal('total_discount', 9, 2)->default(0);
            $table->decimal('total_charges', 9, 2)->default(0);
            $table->decimal('total_sales_tax', 9, 2)->default(0);
            $table->decimal('total_round_off', 9, 2)->default(0);
            $table->decimal('total_amount', 9, 2)->default(0);
            $table->decimal('total_cash_discount', 9, 2)->default(0);
                   
            $table->string('cost_center')->index();
            $table->string('department');
            $table->string('expense_purpose');
            $table->string('posting_profile')->nullable()->index();
            $table->string('accounting_distribution')->nullable()->index();

            $table->integer('created_by')->index();  
            $table->integer('updated_by')->nullable()->index();  
            
            $table->text('description')->nullable();
            $table->string('invoice_account_name')->nullable();

            $table->boolean('is_already_confirmed')->default(false);
            
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
        Schema::dropIfExists('customer_invoices');
    }
}
