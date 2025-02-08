<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdAndClientIdToFiscalCalendarsTable extends Migration
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
            if (!Schema::hasColumn('fiscal_calendars', 'company_id')) {
                $table->integer('company_id');
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
            if (Schema::hasColumn('fiscal_calendars', 'company_id')) {
                $table->dropColumn('company_id');
            }
        });
    }
}
