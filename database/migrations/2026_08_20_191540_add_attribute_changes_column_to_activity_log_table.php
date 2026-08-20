<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributeChangesColumnToActivityLogTable extends Migration
{
    public function up()
    {
        Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
            $table->json('attribute_changes')->nullable()->after('causer_type');
        });
    }

    public function down()
    {
        Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
            $table->dropColumn('attribute_changes');
        });
    }
}
