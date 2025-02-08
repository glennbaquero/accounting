<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCustomerInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            $table->string('line_status')->default('Open Order')->change(); 
            $table->integer('quantity')->default(0)->change(); 
            $table->decimal('unit_price', 20, 9)->default(0)->change(); 
            $table->decimal('unit', 20, 9)->default(0)->change(); 
            $table->decimal('amount', 20, 9)->default(0)->change(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
