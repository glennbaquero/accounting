<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkMainAccountsMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linked_main_accounts_main_accounts', function (Blueprint $table) {
            $table->integer('linked_main_account_id');
            $table->integer('main_account_id');
            $table->timestamps();
        });

        Schema::table("linked_main_accounts", function ($table) {
            if (!Schema::hasColumn('linked_main_accounts', 'deleted_at')) {
                $table->softDeletes();
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
        Schema::dropIfExists('linked_main_accounts_main_accounts');
    }
}
