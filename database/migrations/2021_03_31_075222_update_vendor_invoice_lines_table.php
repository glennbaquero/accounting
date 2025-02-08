<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVendorInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_invoice_lines', 'service_id')) {
                $table->bigInteger('service_id')->nullable();
            }
            if (!Schema::hasColumn('vendor_invoice_lines', 'service_task')) {
                $table->bigInteger('service_task')->nullable();
            }
            if (!Schema::hasColumn('vendor_invoice_lines', 'service_task_details')) {
                $table->bigInteger('service_task_details')->nullable();
            }
            if (!Schema::hasColumn('vendor_invoice_lines', 'rpm_method')) {
                $table->bigInteger('rpm_method')->nullable();
            }
            if (!Schema::hasColumn('vendor_invoice_lines', 'number_of_hours')) {
                $table->bigInteger('number_of_hours')->nullable();
            }
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
