<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->boolean('posted_invoice_checkbox')->default(false);
            $table->date('posting_date')->nullable();
            $table->integer('posted_by')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->boolean('posted_invoice_checkbox')->default(false);
            $table->date('posting_date')->nullable();
            $table->integer('posted_by')->nullable()->index();
        });
    }
}
