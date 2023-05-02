<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => Str::random(10),
            'email' => 'admin@gmail.com',
            'user_name' => 'admin',
            'password' => bcrypt('123456'),
        ]);

    	$faker = Faker::create();
    	foreach (range(1,10) as $index) {
	        DB::table('admins')->insert([
	            'name' => $faker->name,
	            'user_name' => $faker->userName,
	            'email' => $faker->email,
	            'password' => bcrypt('secret'),
	        ]);
		}
    }
}
