<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropDepartmentColumnOnPurchaseOrderLinesTable extends Migration
{
    public function up()
    {
        Schema::table('purchase_order_lines', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_order_lines', 'department')) {
                $table->dropColumn('department');
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
        Schema::table('purchase_order_lines', function (Blueprint $table) {
            //
        });
    }
}
