<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainAccountCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_account_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_account_category_reference')->unique();
            $table->string('main_account_category');
            $table->string('description');
            $table->string('main_account_type');
            $table->string('closed_checkbox')->nullable();            

            $table->integer('created_by')->index();  
            $table->integer('updated_by')->nullable()->index();   

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
        Schema::dropIfExists('main_account_categories');
    }
}