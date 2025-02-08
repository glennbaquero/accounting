<?php
use Illuminate\Database\Seeder;

use App\Models\JournalSetups\PaymentMethod;

class PaymentMethodTableSeeder extends Seeder
{	
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	PaymentMethod::truncate();
		
    	$methods = [
    	    [
    	        'name' => 'Installment',
    	    ],
            [
    	        'name' => 'Down Payment',
    	    ],
            [
    	        'name' => 'Full Payment',
    	    ],
    	];

        foreach($methods as $method) {
        
                PaymentMethod::create($method);
    	}

        
    }
}
