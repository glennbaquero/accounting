<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyNameInVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'company_name')) {
                $table->string('company_name');
            }
            if (Schema::hasColumn('vendors', 'email')) {
                $table->string('email')->nullable()->change();
            }
            if (Schema::hasColumn('vendors', 'phone')) {
                $table->string('phone')->nullable()->change();
            }
            if (Schema::hasColumn('vendors', 'mobile_number')) {
                $table->string('mobile_number')->nullable()->change();
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
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'company_name')) {
                $table->dropColumn('company_name');
            }
        });
    }
}
