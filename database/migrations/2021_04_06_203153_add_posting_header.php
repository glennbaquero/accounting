<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostingHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_posting_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_posting_profiles', 'posting_header_id')) {
                $table->bigInteger('posting_header_id')->nullable();
            }
            if (!Schema::hasColumn('vendor_posting_profiles', 'created_by')) {
                $table->bigInteger('created_by')->nullable();
            }
            if (!Schema::hasColumn('vendor_posting_profiles', 'created_on')) {
                $table->dateTime('created_on')->nullable();
            }
            if (!Schema::hasColumn('vendor_posting_profiles', 'updated_by')) {
                $table->bigInteger('updated_by')->nullable();
            }
            if (!Schema::hasColumn('vendor_posting_profiles', 'updated_on')) {
                $table->dateTime('updated_on')->nullable();
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
