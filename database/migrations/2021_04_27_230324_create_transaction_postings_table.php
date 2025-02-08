<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_postings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('posting_profile');
            $table->text('description')->nullable();
            $table->string('account_code'); // table, group, all
            $table->string('account')->nullable(); // If Table is selected in the Account code field, select the account number of the vendor that is associated with the posting profile
            $table->text('group_number')->nullable(); //If Group is selected, select a vendor group
            $table->string('summary_account')->nullable();
            $table->string('settle_account')->nullable();
            $table->string('sales_tax_prepayments')->nullable();
            $table->string('arrival')->nullable();
            $table->string('offset_account')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->string('summary_account_code')->nullable();
            $table->string('journal_name')->nullable();
            $table->string('offset_account_code')->nullable();
            $table->string('offset_account_type')->default('Ledger');
            $table->string('settle_account_code')->nullable();
            $table->string('document')->nullable();
            $table->string('document_status')->nullable();
            $table->bigInteger('posting_header_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->dateTime('created_on')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->dateTime('updated_on')->nullable();
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
        Schema::dropIfExists('transaction_postings');
    }
}
