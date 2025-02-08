<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCodeControl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_code_controls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->unsigned();
            $table->integer('column_1_type')->unsigned();
            $table->integer('column_2_type')->unsigned();
            $table->string('column_1')->nullable();
            $table->string('column_2')->nullable();
            $table->string('separated_by');
            $table->string('prefix');
            $table->integer('company_id')->unsigned();
            $table->integer('client_id')->unsigned();

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
        Schema::dropIfExists('document_code_controls');
    }
}
