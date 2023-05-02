<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**php artisan make:seeder UsersTableSeeder


     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();

        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => 'user@gmail.com',
            'phone' => $faker->phoneNumber,
            'status' => "ACTIVE",
            'tin' => $faker->randomDigit,
            'password' => bcrypt('123456'),
        ]);

    	foreach (range(1,100) as $index) {
	        DB::table('users')->insert([
	            'name' => $faker->name,
	            'phone' => $faker->phoneNumber,
	            'status' => "ACTIVE",
	            'tin' => $faker->randomDigit,
	            'email' => $faker->email,
	            'password' => bcrypt('secret'),
	        ]);
		}
    }
}
