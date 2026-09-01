<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixedAssetDepreciationLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_asset_depreciation_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asset_id')->index()->nullable();
            $table->integer('period_number')->nullable();
            $table->datetime('period_date')->nullable();
            $table->string('fiscal_period_id')->nullable()->index();
            $table->string('fiscal_period_code')->nullable();
            $table->decimal('depreciation_amount', 15, 2)->default(0);
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->decimal('book_value', 15, 2)->default(0);
            $table->boolean('posted_checkbox')->default(false);
            $table->datetime('posted_on')->nullable();
            $table->integer('posted_by')->nullable();
            $table->integer('general_ledger_line_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('company_id')->nullable();
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
        Schema::dropIfExists('fixed_asset_depreciation_lines');
    }
}
