<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_account_id')->unique();
            // $table->string('main_account_code_number')->nullable();
            // $table->string('main_account_code')->nullable();            
            $table->string('main_account_name')->nullable();
            $table->text('description')->nullable();            

         // $table->string('level_of_main_account_to_display');      
         // $table->string('companies');            
            
            $table->string('main_account_type')->nullable();            
            $table->string('reporting_type')->nullable();            
            $table->string('main_account_category')->nullable();           

            $table->string('db_cr_proposal')->nullable();

            $table->string('db_cr_requirement')->nullable();                                    
            $table->string('balance_control')->nullable();                                    
            $table->string('offset_account')->nullable();
            // $table->string('opening_account');
            $table->string('do_not_allow_manual_entry')->nullable();    
            
            $table->datetime('active_from')->nullable();
            $table->datetime('active_to')->nullable();

            $table->string('suspended')->nullable();
            $table->string('monetary')->nullable(); 
            $table->string('close')->nullable(); 
            $table->string('default_consolidation_account')->nullable();
            $table->string('opening_account')->nullable();                                                                  

         // $table->string('user_id');
         // $table->string('validate_user');

            $table->string('posting_type')->nullable();
            $table->string('validate_posting')->nullable(); 
            $table->string('sales_tax_group')->nullable(); 
            $table->string('item_sales_tax_group')->nullable();
            $table->string('sales_tax_direction')->nullable();  

            $table->string('exempt')->nullable();
            $table->string('sales_tax_code')->nullable(); 
            $table->string('validate_sales_tax')->nullable(); 
            $table->string('invert_sign')->nullable();
            $table->string('column')->nullable();

            $table->string('bold')->nullable();
            $table->string('italics')->nullable(); 
            $table->string('line_above')->nullable(); 
            $table->string('line_below')->nullable();
            $table->string('underline_text')->nullable();
            $table->string('underline_amount')->nullable();

            $table->integer('created_by')->index();  
            $table->integer('updated_by')->nullable()->index();   

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
        Schema::dropIfExists('main_accounts');
    }
}
