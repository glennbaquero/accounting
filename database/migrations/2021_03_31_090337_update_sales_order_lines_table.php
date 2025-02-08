<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSalesOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_order_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_order_lines', 'service_id')) {
                $table->bigInteger('service_id')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'service_task')) {
                $table->bigInteger('service_task')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'service_task_details')) {
                $table->bigInteger('service_task_details')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'rpm_method')) {
                $table->bigInteger('rpm_method')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'number_of_hours')) {
                $table->bigInteger('number_of_hours')->nullable();
            }


            $table->integer('product_id')->nullable()->change();
            $table->integer('variant_id')->nullable()->change();
            $table->text('product')->nullable()->change();
            $table->text('variant')->nullable()->change();
            $table->string('variant_name')->nullable()->change();
            $table->string('variant_number')->nullable()->change();
        });

        Schema::table('sales_order_return_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_order_lines', 'service_id')) {
                $table->bigInteger('service_id')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'service_task')) {
                $table->bigInteger('service_task')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'service_task_details')) {
                $table->bigInteger('service_task_details')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'rpm_method')) {
                $table->bigInteger('rpm_method')->nullable();
            }
            if (!Schema::hasColumn('sales_order_lines', 'number_of_hours')) {
                $table->bigInteger('number_of_hours')->nullable();
            }


            $table->integer('product_id')->nullable()->change();
            $table->integer('variant_id')->nullable()->change();
            $table->text('product')->nullable()->change();
            $table->text('variant')->nullable()->change();
            $table->string('variant_name')->nullable()->change();
            $table->string('variant_number')->nullable()->change();
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
