<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('document_status')->nullable()->change();
            $table->string('approval_status')->nullable()->change();
            $table->string('confirmed_date')->nullable()->change();
            $table->string('accounting_date')->nullable()->change();
            $table->string('accouting_distribution')->nullable();
            $table->string('delivery_contact')->nullable();
            $table->string('delivery_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('accouting_distribution');
            $table->dropColumn('delivery_contact');
            $table->dropColumn('delivery_address');
        });
    }
}
