<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnActiveToDocumentCodeControl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_code_controls', function (Blueprint $table) {
            if (!Schema::hasColumn('document_code_controls', 'active')) {
                $table->boolean('active')->default(false);
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
        Schema::table('document_code_controls', function (Blueprint $table) {
            if (Schema::hasColumn('document_code_controls', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
}
