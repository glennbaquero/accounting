<?php
use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UserTableSeeder extends Seeder
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
				'company_id' => 0,
				'department_id' => 0,
				'position_id' => 0, 
			],
			[
    	        'first_name' => 'Accounting',
    	        'last_name' => 'User',
    	        'email' => 'company@accounting.com',
    	        'password' => 'password',
				'company_id' => 1,
				'department_id' => 1,
				'position_id' => 1, 
    	    ],
			[
    	        'first_name' => 'Accounting',
    	        'last_name' => 'User',
    	        'email' => 'user@accounting.com',
    	        'password' => 'password',
				'company_id' => 1,
				'department_id' => 1,
				'position_id' => 1, 
			],	
    	];

    	foreach($users as $user) {
    	    $user['password'] = Hash::make($user['password']);
    	    $user['email_verified_at'] = now();
    	    
    	    User::create($user);
    	}
    }
}
