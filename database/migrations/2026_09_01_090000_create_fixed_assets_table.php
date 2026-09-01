<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixedAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asset_id')->index()->nullable();
            $table->string('asset_code')->nullable();
            $table->string('asset_name')->nullable();
            $table->text('description')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('company_id')->nullable();

            $table->integer('main_account')->nullable()->index();
            $table->string('main_account_code')->nullable();
            $table->string('main_account_name')->nullable();

            $table->integer('accumulated_depreciation_account')->nullable();
            $table->string('accumulated_depreciation_account_code')->nullable();
            $table->string('accumulated_depreciation_account_name')->nullable();

            $table->integer('depreciation_expense_account')->nullable();
            $table->string('depreciation_expense_account_code')->nullable();
            $table->string('depreciation_expense_account_name')->nullable();

            $table->integer('gain_loss_account')->nullable();
            $table->string('gain_loss_account_code')->nullable();
            $table->string('gain_loss_account_name')->nullable();

            $table->datetime('acquisition_date')->nullable();
            $table->decimal('acquisition_cost', 15, 2)->default(0);
            $table->decimal('salvage_value', 15, 2)->default(0);
            $table->integer('useful_life_months')->nullable();
            $table->string('depreciation_method')->default('Straight-Line')->nullable();

            $table->string('asset_status')->default('Active')->nullable();
            $table->datetime('disposal_date')->nullable();
            $table->decimal('disposal_proceeds', 15, 2)->nullable();
            $table->integer('disposal_proceeds_account')->nullable();
            $table->decimal('disposal_gain_loss', 15, 2)->nullable();

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
        Schema::dropIfExists('fixed_assets');
    }
}
