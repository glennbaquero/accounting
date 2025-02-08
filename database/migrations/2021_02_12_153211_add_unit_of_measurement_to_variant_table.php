<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitOfMeasurementToVariantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {
            if (!Schema::hasColumn('variants', 'unit_of_measurement')) {
                $table->string('unit_of_measurement')->nullable();
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
        Schema::table('variants', function (Blueprint $table) {
            if (Schema::hasColumn('variants', 'unit_of_measurement')) {
                $table->dropColumn('unit_of_measurement');
            }
        });
    }
}
