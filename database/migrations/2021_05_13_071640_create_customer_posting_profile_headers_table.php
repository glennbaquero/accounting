<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPostingProfileHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_posting_profile_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->string('posting_profile')->nullable();
            $table->string('document')->nullable();
            $table->string('description')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->dateTime('updated_on')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->dateTime('created_on')->nullable();
            $table->softDeletes();
            $table->timestamps();

        });

        Schema::table('customer_posting_profiles', function (Blueprint $table) {
            $table->bigInteger('posting_header_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_posting_profile_headers');
    }
}
