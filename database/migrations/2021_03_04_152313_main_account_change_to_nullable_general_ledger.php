<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MainAccountChangeToNullableGeneralLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            if (Schema::hasColumn('general_ledgers', 'main_account_id')) {
                $table->integer('main_account_id')->unsigned()->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            //
        });
    }
}
