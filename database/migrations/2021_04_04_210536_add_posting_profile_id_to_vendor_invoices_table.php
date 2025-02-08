<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostingProfileIdToVendorInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_invoices', 'posting_profile')) {
                $table->dropColumn('posting_profile');
            }  
            if (!Schema::hasColumn('vendor_invoices', 'posting_profile_id')) {
                $table->integer('posting_profile_id')->unsigned()->nullable();
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
        Schema::table('vendor_invoices', function (Blueprint $table) {
            //
        });
    }
}
