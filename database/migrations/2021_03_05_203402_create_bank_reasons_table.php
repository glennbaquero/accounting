<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->string('reason_code')->nullable();
            $table->string('default_comment')->nullable();
            $table->boolean('bank')->default(false);
            $table->boolean('purpose_code')->default(false);
            $table->boolean('cancellation_reason')->default(false);
            
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
        Schema::dropIfExists('bank_reasons');
    }
}
