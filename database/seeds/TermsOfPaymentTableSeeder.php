<?php
use Illuminate\Database\Seeder;

use App\Models\JournalSetups\TermsOfPayment;

class TermsOfPaymentTableSeeder extends Seeder
{	
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	TermsOfPayment::truncate();
		
    	$terms = [
    	    [
    	        'terms_of_payment' => 'Installment Term',
                'payment_method_id' => 1,
                'months' => 12,
                'days' => 30,
                'payment_day' => 15,
                'payment_schedule' => 30,
                'cutoff_day' => 30,
                'description' => 'installment example'
    	    ],
            [       
    	        'terms_of_payment' => 'Down Payment Term',
                'payment_method_id' => 2,
                'months' => 12,
                'days' => 30,
                'payment_day' => 15,
                'payment_schedule' => 30,
                'cutoff_day' => 30,
                'description' => 'downpayment example'
    	    ],
            [
    	        'terms_of_payment' => 'Full Payment Term',
                'payment_method_id' => 3,
                'months' => 12,
                'days' => 30,
                'payment_day' => 15,
                'payment_schedule' => 30,
                'cutoff_day' => 30,
                'description' => 'fullpayment example'
    	    ],
    	];

        foreach($terms as $term) {
            TermsOfPayment::create($term);
    	}

        
    }
}
