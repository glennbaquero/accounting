<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankFacilityGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_facility_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('company_id')->unsigned()->index();            
            $table->bigInteger('client_id')->unsigned()->index();            
            $table->string('bank_facility_group_code');
            $table->string('bank_facility_group_name');
            $table->text('description');

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
        Schema::dropIfExists('bank_facility_groups');
    }
}
