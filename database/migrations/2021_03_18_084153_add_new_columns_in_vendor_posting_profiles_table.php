<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInVendorPostingProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_posting_profiles', function (Blueprint $table) {
            $table->string('summary_account_code')->nullable();
            $table->string('journal_name')->nullable();
            $table->string('offset_account_code')->nullable();
            $table->string('offset_account_type')->default('Ledger');
            $table->string('settle_account_code')->nullable();
            $table->string('document')->nullable();
            $table->string('document_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_posting_profiles', function (Blueprint $table) {
            //
        });
    }
}
