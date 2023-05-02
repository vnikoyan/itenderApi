<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderStateSeeder extends Seeder
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
	        DB::table('order_state')->insertOrIgnore([
	            'user_id'    => $faker->numberBetween(1,100),
	            'package_id_one_person' => $faker->numberBetween(1,8),
	            'package_id_competitive' => $faker->numberBetween(9,16),
	            'strat_date' => $faker->dateTime(),
	            'end_date'   => $faker->dateTime(),
                'payment_method'   => 'Idram',
                'amount_paid'   => 1000,
			]);
        }
        
    }
}
