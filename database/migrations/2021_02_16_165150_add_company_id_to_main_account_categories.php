<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdToMainAccountCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_account_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('main_account_categories', 'comapany_id')) {
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
        Schema::table('main_account_categories', function (Blueprint $table) {
            //
        });
    }
}
