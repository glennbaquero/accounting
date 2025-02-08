<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyTableToVendorSetups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_days', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_days', 'company_id')) {
                $table->integer('company_id');
            }
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'company_id')) {
                $table->integer('company_id');
            }
        });

        Schema::table('terms_of_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('terms_of_payments', 'company_id')) {
                $table->integer('company_id');
            }
        });

        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'payment_days_id')) {
                $table->integer('payment_day_id')->nullable();
            }
            if (Schema::hasColumn('vendors', 'payment_days')) {
                $table->dropColumn('payment_days');
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
        Schema::table('payment_days', function (Blueprint $table) {
            //
        });
    }
}
