<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropColumnV4SalesOrdersTables extends Migration
{
    private static $tableName = 'sales_order_lines';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'cost_center')) {
                $table->dropColumn('cost_center');
            }
            
            if (Schema::hasColumn(static::$tableName, 'department')) {
                $table->dropColumn('department');
            }

            if (Schema::hasColumn(static::$tableName, 'expense_purpose')) {
                $table->dropColumn('expense_purpose');
            }
            
            if (!Schema::hasColumn(static::$tableName, 'dimension_value_cost_center_id')) {
                $table->integer('dimension_value_cost_center_id')->unsigned()->index();
            }

            if (!Schema::hasColumn(static::$tableName, 'dimension_value_department_id')) {
                $table->integer('dimension_value_department_id')->unsigned()->index();
            }

            if (!Schema::hasColumn(static::$tableName, 'dimension_value_expense_purpose_id')) {
                $table->integer('dimension_value_expense_purpose_id')->unsigned()->index();
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
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'cost_center')) {
                $table->string('cost_center');
            }
            
            if (! Schema::hasColumn(static::$tableName, 'department')) {
                $table->string('department');
            }

            if (! Schema::hasColumn(static::$tableName, 'expense_purpose')) {
                $table->string('expense_purpose');
            }
            
            if (Schema::hasColumn(static::$tableName, 'dimension_value_cost_center_id')) {
                $table->dropColumn('dimension_value_cost_center_id');
            }

            if (Schema::hasColumn(static::$tableName, 'dimension_value_department_id')) {
                $table->dropColumn('dimension_value_department_id');
            }

            if (Schema::hasColumn(static::$tableName, 'dimension_value_expense_purpose_id')) {
                $table->dropColumn('dimension_value_expense_purpose_id');
            }
        });
    }
}
