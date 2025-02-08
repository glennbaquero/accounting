<?php

use App\Models\JournalSetups\CashDiscount;
use Illuminate\Database\Seeder;

class CashDiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CashDiscount::truncate();
        $items = [
            [
                'next_discount_code' => 'NDC#1',
                'months' => '5',
                'days' => '12',
                'description' => '<p>Integer consectetur urna ut risus interdum sagittis. Curabitur sit amet risus orci. Maecenas condimentum pretium commodo. Donec mauris lacus, dictum sed mi sed, sagittis hendrerit tortor. Phasellus faucibus nec dui eu condimentum. Proin quis posuere elit, sed posuere mauris. Quisque commodo euismod dui in tempus.</p>',
                'net_or_current' => 'Net',
                'discount_offset_accounts' => 'DOA#1',
                'discount_cash' => 100,
                'discount_percent' => 15.5,
                'customer_account' => 1,
                'vendor_account' => 1,
            ],
            [
                'next_discount_code' => 'NDC#2',
                'months' => '3',
                'days' => '18',
                'description' => '<p>Integer consectetur urna ut risus interdum sagittis. Curabitur sit amet risus orci. Maecenas condimentum pretium commodo. Donec mauris lacus, dictum sed mi sed, sagittis hendrerit tortor. Phasellus faucibus nec dui eu condimentum. Proin quis posuere elit, sed posuere mauris. Quisque commodo euismod dui in tempus.</p>',
                'net_or_current' => 'Current',
                'discount_offset_accounts' => 'DOA#2',
                'discount_cash' => 200,
                'discount_percent' => 10,
                'customer_account' => 1,
                'vendor_account' => 1,
            ],
            [
                'next_discount_code' => 'NDC#3',
                'months' => '8',
                'days' => '22',
                'description' => '<p>Integer consectetur urna ut risus interdum sagittis. Curabitur sit amet risus orci. Maecenas condimentum pretium commodo. Donec mauris lacus, dictum sed mi sed, sagittis hendrerit tortor. Phasellus faucibus nec dui eu condimentum. Proin quis posuere elit, sed posuere mauris. Quisque commodo euismod dui in tempus.</p>',
                'net_or_current' => 'Net',
                'discount_offset_accounts' => 'DOA#3',
                'discount_cash' => 300,
                'discount_percent' => 20,
                'customer_account' => 1,
                'vendor_account' => 1,
            ]
        ];

        foreach($items as $term) {
            CashDiscount::create($term);
        }
    }
}
