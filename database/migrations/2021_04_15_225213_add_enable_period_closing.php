<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnablePeriodClosing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            if (!Schema::hasColumn('general_ledgers', 'enabled_closing_by')) {
                $table->integer('enabled_closing_by')->nullable()->unsigned();
            }

            if (!Schema::hasColumn('general_ledgers', 'enabled_closing_date')) {
                $table->date('enabled_closing_date')->nullable();
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
        //
    }
}
