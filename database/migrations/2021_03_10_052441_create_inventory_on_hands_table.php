<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryOnHandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_on_hands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_on_hand_number')->unique();
            $table->string('item_number');
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->decimal('item_unit', 20, 9)->default(0);
            $table->boolean('ordered')->default(false);
            $table->decimal('ordered_quantity', 20, 9)->default(0);
            $table->decimal('physical_inventory', 20, 9)->default(0);
            $table->boolean('received')->default(false);
            $table->decimal('received_quantity', 20, 9)->default(0);
            $table->decimal('posted_quantity', 20, 9)->default(0);
            $table->decimal('total_available', 20, 9)->default(0);
            $table->decimal('physical_cost_amount', 20, 9)->default(0);
            $table->decimal('financial_cost_amount', 20, 9)->default(0);
            $table->boolean('closed_inventory_checkbox')->default(false);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('inventory_on_hands');
    }
}
