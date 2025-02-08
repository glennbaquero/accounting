<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();

            $table->string('collection_id')->nullable();
            $table->dateTime('collection_date')->nullable();
            $table->dateTime('sent_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->decimal('amount_to_settle', 9, 2)->default(0)->nullable();

            $table->string('customer_account')->nullable();
            $table->string('invoice_account')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_contact_id')->nullable();
            $table->string('customer_bank_account')->nullable();
            $table->string('client_bank_account')->nullable();
            $table->string('description')->nullable();
            $table->integer('bills_exchange_id')->nullable();
            $table->string('bills_exchange_status')->nullable();
            $table->string('voucher')->nullable();

            $table->string('collection_status')->nullable();
            $table->boolean('closed_checkbox')->default(false)->nullable();
            $table->dateTime('closed_date')->nullable();
            $table->integer('closed_by')->nullable();
            $table->boolean('posted_checkbox')->default(false)->nullable();
            $table->dateTime('posted_date')->nullable();
            $table->integer('posted_by')->nullable();
            $table->string('activity_type')->nullable();
            $table->dateTime('activity_start_date')->nullable();
            $table->dateTime('activity_date')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
