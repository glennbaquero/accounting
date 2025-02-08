<?php

use Illuminate\Database\Seeder;

use App\Models\Permissions\PermissionCategory;
use App\Models\Permissions\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permission_ids = Permission::all()->pluck('id');
        Permission::whereIn('id', $permission_ids)->delete();
        
        $categories = [

            [
                'name' => 'Purchase',
                'description' => 'Manage Purchase Module',
                'icon' => 'fas fa-shopping-cart',
                'items' => [
                    [
                        'name' => 'purchase-order.index',
                        'description' => 'Manage Purchase Order',
                    ],
                    [
                        'name' => 'vendor-invoice.index',
                        'description' => 'Manage Vendor Invoice',
                    ],
                    [
                        'name' => 'vendor-payment.index',
                        'description' => 'Manage Vendor Payment',
                    ],
                    [
                        'name' => 'vendor.index',
                        'description' => 'Manage Vendor',
                    ],
                ],
            ],

            
            [
                'name' => 'Sales',
                'description' => 'Manage Sales Module',
                'icon' => 'fas fa-chart-line',
                'items' => [
                    [
                        'name' => 'sales-order.index',
                        'description' => 'Manage Purchase Order',
                    ],
                    [
                        'name' => 'customer-invoice.index',
                        'description' => 'Manage Customer Invoice',
                    ],
                    [
                        'name' => 'customer-payment.index',
                        'description' => 'Manage Customer Payment',
                    ],
                    [
                        'name' => 'customer.index',
                        'description' => 'Manage Customer',
                    ],
                ],
            ],
            [
                'name' => 'Accounts Payable',
                'description' => 'Manage Accounts Payable Journals',
                'icon' => 'fas fa-money-bill',
                'items' => [
                    [
                        'name' => 'invoice-approval-journal.index',
                        'description' => 'Manage Invoice Approval Journal',
                    ],
                    [
                        'name' => 'vendor-payment-journal.index',
                        'description' => 'Manage Vendor Payment Journal',
                    ],
                ],
            ],
            [
                'name' => 'Accounts Recievable',
                'description' => 'Manage Accounts Recievable',
                'icon' => 'fas fa-id-card',
                'items' => [
                    [
                        'name' => 'customer-invoice-journal.index',
                        'description' => 'Manage Customer Invoice Journal',
                    ],
                    [
                        'name' => 'customer-payment-journal.index',
                        'description' => 'Manange Customer Payment Journal',
                    ],
                ],
            ],
            [
                'name' => 'General Ledger',
                'description' => 'Manage General Ledger Module',
                'icon' => 'fas fa-scroll',
                'items' => [
                    [
                        'name' => 'general-ledger.index',
                        'description' => 'Manage General Ledger',
                    ],

                ],
            ],
        ];

    	foreach ($categories as $category) {
            $permissions = $category['items'];
            unset($category['items']);

            $item = PermissionCategory::where('name', $category['name'])->first();

            if (!$item) {
                $this->command->info('Adding permission category ' . $category['name'] . '...');
                $item = PermissionCategory::create($category);
            } else {
                $this->command->warn('Updating permission category ' . $category['name'] . '...');
                $item->update($category);
            }

            foreach ($permissions as $permission) {
                $permissionItem = Permission::where('name', $permission['name'])->first();
                
                if (!$permissionItem) {
                    $this->command->info('Adding permission ' . $permission['name'] . '...');
                    $item->permissions()->create($permission);
                } else {
                    $this->command->warn('Updating permission ' . $permission['name'] . '...');
                    unset($permission['name']);
                    $permissionItem->update($permission);
                }
            }
    	}
    }
}
