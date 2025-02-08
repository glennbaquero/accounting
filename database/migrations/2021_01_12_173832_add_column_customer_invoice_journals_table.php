<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCustomerInvoiceJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('customer_invoice_journals', function (Blueprint $table) {
                $table->string('cost_center')->nullable();
                $table->string('department')->nullable();
                $table->string('expense_purpose')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoice_journals', function (Blueprint $table) {
            $table->dropColumn('cost_center');
            $table->dropColumn('department');
            $table->dropColumn('expense_purpose');
        });
    }
}