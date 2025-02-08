<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPasswordColumnForClosingTransationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('closing_transactions', 'password_set_by')) {
                $table->integer('password_set_by')->unsigned()->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'password_set_on')) {
                $table->dateTime('password_set_on')->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'password')) {
                $table->string('password')->nullable();
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
