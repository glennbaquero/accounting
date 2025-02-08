<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelColumnInPurchaseOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_returns', function (Blueprint $table) {
            $table->boolean('is_cancelled')->default(false);
            $table->timestamp('cancelled_on')->nullable();
            $table->bigInteger('cancelled_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_returns', function (Blueprint $table) {
            //
        });
    }
}
