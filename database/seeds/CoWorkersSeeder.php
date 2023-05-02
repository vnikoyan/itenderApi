<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CoWorkersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1,10) as $key => $index) {
	        DB::table('co_workers')->insertOrIgnore([
	            'user_id'    => $faker->numberBetween(1,100),
	            'address'    => $faker->address,
	            'website'    => $faker->url,
                'cpv' => "ավազ և կավ",
                'image' => "/co_workers.png",
			]);
			
		}

        //
    }
}
