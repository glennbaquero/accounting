<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxTableLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_table_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            $table->integer('client_id')->nullable();
            $table->integer('tax_id')->nullable();
            
            $table->string('tax_name')->nullable();
            $table->string('tax_posting_id')->nullable();
            $table->string('tax_posting')->nullable();
            $table->string('description')->nullable();
            $table->string('level')->nullable();
            $table->string('applied_to')->nullable();
            $table->decimal('tax_percent', 9, 2)->default(0)->nullable();
            $table->boolean('peza_checkbox')->default(false)->nullable();
            $table->boolean('vat_exempt_number_checkbox')->default(false)->nullable();
            $table->string('major_industry_clasification')->nullable();
            $table->string('industry_clasification_group')->nullable();
            $table->string('psic_sections')->nullable();
            $table->string('psic_divisions')->nullable();
            $table->string('psic_groups')->nullable();
            $table->string('psic_class')->nullable();
            $table->string('psic_subclass')->nullable();

            $table->string('procurement_posting')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('service_task_id')->nullable();
            $table->string('delivery_type')->nullable();

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
        Schema::dropIfExists('tax_table_lines');
    }
}
