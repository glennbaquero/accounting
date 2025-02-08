<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->integer('quantity')->unsigned();
            $table->decimal('unit_price', 8, 2);

            $table->boolean('is_available')->default(false);
            $table->integer('company_id')->unsigned();
            
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
        Schema::dropIfExists('variants');
    }
}
