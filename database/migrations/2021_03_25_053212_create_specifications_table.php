<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_specification')->unique();
            $table->bigInteger('client_id');
            $table->bigInteger('company_id');
            $table->string('specification_name');
            $table->text('description');
            $table->string('construction')->nullable();
            $table->string('fibre')->nullable();
            $table->string('dye_method')->nullable();
            $table->string('gauge')->nullable();
            $table->string('size');
            $table->string('yarn');
            $table->string('average_density')->nullable();
            $table->string('tufted_weight')->nullable();
            $table->string('production_weight')->nullable();
            $table->string('total_thickness')->nullable();
            $table->string('secondary_backing')->nullable();
            $table->string('recommended_installation')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specifications');
    }
}
