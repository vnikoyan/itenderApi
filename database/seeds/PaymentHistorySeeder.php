<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

    	foreach (range(1,100) as $key => $index) {
	        DB::table('payment_history')->insertOrIgnore([
	            'user_id'    => $faker->numberBetween(1,100),
	            'strat_date' => $faker->dateTime(),
                'payment_method'   => 'Idram',
                'amount_paid'   => 1000,
			]);
			
		}
    }
}
