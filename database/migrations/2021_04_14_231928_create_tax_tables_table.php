<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            $table->integer('client_id')->nullable();
            $table->string('tax_posting')->nullable();
            $table->string('tax_posting_name')->nullable();
            $table->string('description')->nullable();
            $table->decimal('tax_percent', 9, 2)->default(0)->nullable();
            $table->boolean('peza_checkbox')->default(false)->nullable();
            $table->boolean('vat_exempt_number_checkbox')->default(false)->nullable();
            
            $table->string('tax_account_code_number')->nullable();
            $table->string('tax_account')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

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
        Schema::dropIfExists('tax_tables');
    }
}
