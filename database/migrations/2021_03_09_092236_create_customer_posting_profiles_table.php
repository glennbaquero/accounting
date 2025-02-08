<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPostingProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_posting_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('posting_profile');
            $table->text('description')->nullable();
            $table->string('account_code'); // table, group, all
            $table->string('account')->nullable(); // If Table is selected in the Account code field, select the account number of the customer that is associated with the posting profile
            $table->text('group_number')->nullable(); //If Group is selected, select a customer group
            $table->string('summary_account')->nullable();
            $table->string('settle_account')->nullable();
            $table->string('sales_tax_prepayments')->nullable();
            $table->string('arrival')->nullable();
            $table->string('offset_account')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_posting_profiles');
    }
}
