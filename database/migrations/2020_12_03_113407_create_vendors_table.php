<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_account')->unique();

            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('email');
            $table->string('company');
            $table->string('display_name');
            $table->string('phone')->nullable();
            $table->text('phone_calling_code')->nullable();
            $table->string('fax')->nullable();
            $table->string('mobile_number');
            $table->text('mobile_calling_code')->nullable();

            $table->string('other')->nullable();
            $table->string('website')->nullable();
            
            $table->text('notes')->nullable();

            $table->integer('method_of_payment')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('payment_specification')->nullable();
            $table->string('tax_exempt_number')->nullable();
            $table->string('payment_type')->nullable();

            $table->string('payment_days')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('use_cash_discount')->nullable();
            $table->string('payment_schedule')->nullable();
            $table->string('bank_account')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
