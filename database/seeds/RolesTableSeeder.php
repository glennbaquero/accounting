<?php
use Illuminate\Database\Seeder;

use App\Models\Users\User;
use App\Models\Roles\Role;
use App\Models\Permissions\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin'],
            ['name' => 'Admin'],
            ['name' => 'Company Admin'],
        ];

        foreach ($roles as $role) {
            $role = Role::updateOrCreate($role);
            $role->syncPermissions(Permission::all());
        }

        $admin = User::first();

        $admin->assignRole(Role::first());

        $company = User::find(2);
        $company->assignRole('Company Admin');
    }
}
