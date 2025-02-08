<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->string('level')->default('Main');
            $table->string('applied_to')->default('Customer');
            $table->bigInteger('vendor_payment_method_id')->unsigned()->index();
            $table->bigInteger('customer_payment_method_id')->unsigned()->index();
            $table->bigInteger('procurement_id')->unsigned()->index();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('variant_id')->unsigned()->index();
            $table->bigInteger('service_id')->unsigned()->index();
            $table->bigInteger('service_task_id')->unsigned()->index();
            $table->string('delivery_type')->default('Air');
            $table->string('charge_category')->default('Fixed Amount');
            $table->decimal('charge_value', 20, 2)->default(0);
            $table->decimal('from_amount', 20, 2)->default(0);
            $table->decimal('to_amount', 20, 2)->default(0);
            $table->bigInteger('quantity')->default(0);
            $table->decimal('charge_percentage',20, 2)->default(0);
            $table->bigInteger('main_account_id')->unsigned()->index();
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
        Schema::dropIfExists('charges');
    }
}
