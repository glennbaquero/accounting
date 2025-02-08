<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv2FiscalCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiscal_calendars', function (Blueprint $table) {
            if (!Schema::hasColumn('fiscal_calendars', 'client_id')) {
                 $table->integer('client_id');
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
        Schema::table('fiscal_calendars', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });    
    }
}
