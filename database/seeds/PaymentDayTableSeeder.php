<?php

use App\Models\JournalSetups\PaymentDay;
use Illuminate\Database\Seeder;

class PaymentDayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentDay::truncate();
        $items = [
            [
                'payment_day' => 'FRI-WEEK',
                'week_month' => 'Week',
                'description' => '<p>Friday weekly.</p>',
                'day_of_week' => 'Friday',
            ],
            [
                'payment_day' => 'MON-WEEK',
                'week_month' => 'Week',
                'description' => '<p>Monday weekly.</p>',
                'day_of_week' => 'Monday',
            ],
            [
                'payment_day' => 'HALF-MONTH',
                'week_month' => 'Month',
                'description' => '<p>15th day of month.</p>',
                'day_of_month' => '15',
            ]
        ];
        
        foreach($items as $term) {
            PaymentDay::create($term);
    	}
    }
}
