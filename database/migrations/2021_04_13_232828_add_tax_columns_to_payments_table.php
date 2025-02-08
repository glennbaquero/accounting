<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxColumnsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->decimal('total_sales_vat_exclusive', 9, 2)->default(0)->nullable();
            $table->decimal('less_discount', 9, 2)->default(0)->nullable();
            $table->decimal('add_charge', 9, 2)->default(0)->nullable();
            $table->decimal('add_12_vat', 9, 2)->default(0)->nullable();
            $table->decimal('total_sales_vat_inclusive', 9, 2)->default(0)->nullable();
            $table->decimal('less_withholding_tax', 9, 2)->default(0)->nullable();
            $table->decimal('amount_due', 9, 2)->default(0)->nullable();
            
            $table->decimal('vatable_sales', 9, 2)->default(0)->nullable();
            $table->decimal('vatexempt_sales', 9, 2)->default(0)->nullable();
            $table->decimal('zero_rated_sales', 9, 2)->default(0)->nullable();
            $table->decimal('vat_amount', 9, 2)->default(0)->nullable();
            $table->decimal('total_amount_due', 9, 2)->default(0)->nullable();

            $table->decimal('cash_amount', 9, 2)->default(0)->nullable();
            $table->decimal('other_amount', 9, 2)->default(0)->nullable();
            $table->decimal('total_amount_received', 9, 2)->default(0)->nullable();
            $table->decimal('outstanding', 9, 2)->default(0)->nullable();
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->decimal('total_sales_vat_exclusive', 9, 2)->default(0)->nullable();
            $table->decimal('less_discount', 9, 2)->default(0)->nullable();
            $table->decimal('add_charge', 9, 2)->default(0)->nullable();
            $table->decimal('add_12_vat', 9, 2)->default(0)->nullable();
            $table->decimal('total_sales_vat_inclusive', 9, 2)->default(0)->nullable();
            $table->decimal('less_withholding_tax', 9, 2)->default(0)->nullable();
            $table->decimal('amount_due', 9, 2)->default(0)->nullable();
            
            $table->decimal('vatable_sales', 9, 2)->default(0)->nullable();
            $table->decimal('vatexempt_sales', 9, 2)->default(0)->nullable();
            $table->decimal('zero_rated_sales', 9, 2)->default(0)->nullable();
            $table->decimal('vat_amount', 9, 2)->default(0)->nullable();
            $table->decimal('total_amount_due', 9, 2)->default(0)->nullable();

            $table->decimal('cash_amount', 9, 2)->default(0)->nullable();
            $table->decimal('other_amount', 9, 2)->default(0)->nullable();
            $table->decimal('total_amount_received', 9, 2)->default(0)->nullable();
            $table->decimal('outstanding', 9, 2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropColumn('total_sales_vat_exclusive');
            $table->dropColumn('less_discount');
            $table->dropColumn('add_charge');
            $table->dropColumn('add_12_vat');
            $table->dropColumn('total_sales_vat_inclusive');
            $table->dropColumn('less_withholding_tax');
            $table->dropColumn('amount_due');
            
            $table->dropColumn('vatable_sales');
            $table->dropColumn('vatexempt_sales');
            $table->dropColumn('zero_rated_sales');
            $table->dropColumn('vat_amount');
            $table->dropColumn('total_amount_due');

            $table->dropColumn('cash_amount');
            $table->dropColumn('other_amount');
            $table->dropColumn('total_amount_received');
            $table->dropColumn('outstanding');
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->dropColumn('total_sales_vat_exclusive');
            $table->dropColumn('less_discount');
            $table->dropColumn('add_charge');
            $table->dropColumn('add_12_vat');
            $table->dropColumn('total_sales_vat_inclusive');
            $table->dropColumn('less_withholding_tax');
            $table->dropColumn('amount_due');
            
            $table->dropColumn('vatable_sales');
            $table->dropColumn('vatexempt_sales');
            $table->dropColumn('zero_rated_sales');
            $table->dropColumn('vat_amount');
            $table->dropColumn('total_amount_due');

            $table->dropColumn('cash_amount');
            $table->dropColumn('other_amount');
            $table->dropColumn('total_amount_received');
            $table->dropColumn('outstanding');
        });
    }
}
