<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    { 
      Model::unguard();
      $this->call('PaymentMethodTableSeeder');
      $this->call('UserTableSeeder');
      $this->call('PaymentDayTableSeeder');
      $this->call('TermsOfPaymentTableSeeder');
      $this->call('CashDiscountTableSeeder');
      $this->call('ChartOfAccountTableSeeder');
      $this->call('VendorPaymentTableSeeder');
      $this->call('VendorPaymentLineTableSeeder');
      $this->call('PermissionsTableSeeder');
      Model::reguard();
    }
}
