<?php
use Illuminate\Database\Seeder;

use App\Models\Users\User;

class FinancialDimensionTableSeeder extends Seeder
{	

	protected $users;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	User::truncate();
		
    	$users = [
    	    [
    	        'first_name' => 'Accounting',
    	        'last_name' => 'Admin',
    	        'email' => 'admin@accounting.com',
    	        'password' => 'password',
    	    ],
    	];

    	foreach($users as $user) {
    	    $user['password'] = Hash::make($user['password']);
    	    $user['email_verified_at'] = now();
    	    
    	    User::create($user);
    	}

        factory(User::class, 5)->create();
    }
}
