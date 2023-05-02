<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ItenderSeeder extends Seeder
{
    /** 
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
    	foreach (range(1,40) as $index) {
	        DB::table('itender')->insert([
	            'code' => "iTG-".$faker->buildingNumber,
	            'cpv' => $faker->numberBetween(1,1250),
	            'user_id' => $faker->numberBetween(12,70),
	            'tiem' => $faker->numberBetween(5,20),
	            'count' => $faker->numberBetween(1,50),
	            'type' => "NEW",
	        ]);
		}
    }
}
