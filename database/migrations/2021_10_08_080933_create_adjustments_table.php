<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('adjustment_number')->unique();
            $table->date('adjustment_date');
            $table->bigInteger('adjustment_by')->unsigned()->index();
            $table->boolean('adjustment_checkbox')->default(false);
            $table->string('status')->default('New');
            $table->string('type')->default('New');
            $table->string('sub_type')->default('New');
            $table->string('other_adjustment')->default('New');
            $table->date('issue_date');
            $table->string('status');
            $table->decimal('amount',9 ,2)->default(0);
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
        Schema::dropIfExists('adjustments');
    }
}
