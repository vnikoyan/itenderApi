<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ItenderItemSeeder extends Seeder
{
    /**
     * Run the database seeds. 

     *
     * @return void
     */
    public function run(){

        $faker = Faker::create();
    	foreach (range(1,40) as $index) {
	        DB::table('itender_item')->insert([
                'itender_id' => $faker->numberBetween(1,41),
	            'cpv' => $faker->numberBetween(1,1250),
	            'tex_info' => $faker->text,
	            'purchase_conditions' => $faker->text,
	            'terms_of_payment' => $faker->text,
	            'unit' => 1,
	            'count' => $faker->numberBetween(1,50),
	            'min_step' => $faker->numberBetween(1,3),
	            'min_allowable_price' => 0,
	            'maximum_share_price' => $faker->numberBetween(1,5000),
	            'total_maximum_share_price' => $faker->numberBetween(1,5000),
	        ]);
		}
    }
}
