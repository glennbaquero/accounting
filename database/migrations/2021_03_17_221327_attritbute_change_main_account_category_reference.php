<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AttritbuteChangeMainAccountCategoryReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_account_categories', function (Blueprint $table) {
            if (Schema::hasColumn('main_account_categories', 'main_account_category_reference')) {
                $table->dropUnique(['main_account_category_reference']);
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
