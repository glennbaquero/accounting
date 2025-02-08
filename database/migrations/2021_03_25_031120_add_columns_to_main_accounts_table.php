<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('main_accounts', 'not_sufficient_account')) {
                $table->string('not_sufficient_account')->nullable();
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
        Schema::table('main_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('main_accounts', 'not_sufficient_account')) {
                $table->dropColumn('not_sufficient_account');
            }
        });
    }
}
