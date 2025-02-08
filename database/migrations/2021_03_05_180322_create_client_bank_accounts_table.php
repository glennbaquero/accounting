<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->string('customer_account')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_groups')->nullable();
            $table->date('active_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('bank_account_status')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('bank_account_type')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('iban')->nullable();
            $table->string('post_fee_checkbox')->nullable();
            $table->string('fee_account')->nullable();
            $table->string('clearing')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->string('expense_purpose')->nullable();
            $table->string('text_code')->nullable();
            $table->string('message_to_bank')->nullable();
            $table->string('address')->nullable();
            $table->string('name_of_person')->nullable();
            $table->string('telephone')->nullable();
            $table->string('extension')->nullable();
            $table->string('pager')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('sms')->nullable();
            $table->string('internet_address')->nullable();
            $table->string('telex_number')->nullable();
            $table->string('client_id')->nullable();

            $table->string('posting_profile')->nullable();
            $table->string('accouting_distribution')->nullable();
            $table->string('division')->nullable();
            $table->string('managed_by')->nullable();
            $table->string('authorized_by')->nullable();

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
        Schema::dropIfExists('client_bank_accounts');
    }
}
