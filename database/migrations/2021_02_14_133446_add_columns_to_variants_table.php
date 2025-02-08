<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {
            if (!Schema::hasColumn('variants', 'column')) {
                $table->decimal('unit_price', 20,2)->default(0.00)->change();
            }

            if (!Schema::hasColumn('variants', 'threshold_waring')) {
                $table->integer('threshold_waring')->nullable();   
            }

            if (!Schema::hasColumn('variants', 'threshold_danger')) {
                $table->integer('threshold_danger')->nullable();
            }

            if (!Schema::hasColumn('variants', 'watch')) {
                $table->boolean('watch')->default(false);
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

            if (Schema::hasColumn('variants', 'column')) {
                $table->dropColumn('unit_price');
            }
            if (Schema::hasColumn('variants', 'threshold_waring')) {
                $table->dropColumn('threshold_waring');
            }
            if (Schema::hasColumn('variants', 'threshold_danger')) {
                $table->dropColumn('threshold_danger');
            }
            if (Schema::hasColumn('variants', 'watch')) {
                $table->dropColumn('watch');
            }             
        });
    }
}
