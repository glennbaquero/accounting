<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_account')->unique();

            $table->string('parent_customer_account')->nullable()->index();
            $table->string('bill_parent_customer_account')->nullable()->index();

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

            $table->string('billing_province')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_postal_code')->nullable();

            $table->string('shipping_province')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_street')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_postal_code')->nullable();

            $table->string('other')->nullable();
            $table->string('website')->nullable();
            
            $table->text('notes')->nullable();
            $table->string('language')->nullable();
            $table->string('tax_register_number')->nullable();

            $table->boolean('is_sub_customer')->default(false);

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
        Schema::dropIfExists('customers');
    }
}
