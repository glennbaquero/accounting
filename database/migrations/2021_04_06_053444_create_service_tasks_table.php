<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service_task')->unique();
            $table->bigInteger('service_id')->unsigned()->index();
            $table->string('service');
            $table->string('rpm_method');
            $table->string('service_responsible')->nullable();
            $table->string('period')->nullable();
            $table->decimal('base_hour', 20, 2)->default(0);
            $table->decimal('unit_price', 20, 2)->default(0);
            $table->text('description')->nullable();

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
        Schema::dropIfExists('service_tasks');
    }
}
