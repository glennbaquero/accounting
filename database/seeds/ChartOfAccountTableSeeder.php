<?php

use App\Models\LedgerSetup\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChartOfAccount::truncate();
        $items = [
            [
                'coa_id' => '1000',
                'coa_code' => 'COCC#1',
                'coa_name' => 'COA#1',
                'main_account_mask' => 'MAM#1',
                'description' => '<p>This is a description.</p>',
                'created_by' => 1
            ],
            [
                'coa_id' => '1100',
                'coa_code' => 'COCC#2',
                'coa_name' => 'COA#2',
                'main_account_mask' => 'MAM#2',
                'description' => '<p>This is a description.</p>',
                'created_by' => 1
            ],
            [
                'coa_id' => '1200',
                'coa_code' => 'COCC#3',
                'coa_name' => 'COA#3',
                'main_account_mask' => 'MAM#3',
                'description' => '<p>This is a description.</p>',
                'created_by' => 1
            ]
        ];
        foreach($items as $term) {
            ChartOfAccount::create($term);
        }
    }
}
