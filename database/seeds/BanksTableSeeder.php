<?php

use Illuminate\Database\Seeder;
use App\Bank;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$items = [
    		[
    			'bank_name' => 'BDO Unibank, Inc', 
    			'bank_address' => 'BDO Corporate Center, 7899 Makati Ave. cor. H.V. dela Costa St., Brgy. Bel-Air, City of Makati', 
    			'contact_number' => '(02) 8840-7000',
    		],
    		[
    			'bank_name' => 'Bank of the Philippine Islands', 
    			'bank_address' => 'G/F Makati Stock Exchange Bldg., Ayala Ave., Brgy. Bel-Air, City of Makati', 
    			'contact_number' => '(02) 8246-5953',
    		],
    		[
    			'bank_name' => 'China Banking Corporation', 
    			'bank_address' => '8745 Paseo de Roxas cor. Villar St., Brgy. Bel-Air, City of Makati', 
    			'contact_number' => '(02) 8885-5951',
    		]
    	];


    	foreach($items as $item) {
    		$bank = Bank::updateOrCreate([
    			'bank_name' => $item['bank_name']
    		],[
    			'bank_address' => $item['bank_address'],
    			'contact_number' => $item['contact_number'],
    		]);
    	}

    }
}
