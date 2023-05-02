<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersStateTableSeeder extends Seeder
{
    /**php artisan make:seeder UsersStateTableSeeder
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();


    	foreach (range(1,100) as $index) {
            $name = [
                "hy" => $faker->name,
                "ru" => $faker->name,
                "en" => $faker->name,
            ];

	       $id =  DB::table('users_state_organisation')->insertGetId([
	            'tin'             => $faker->randomDigit,
	            'name'            => json_encode($name),
	            'company_type'    => json_encode($name),
                'phone'           => $faker->phoneNumber,
                'nickname'        =>  json_encode($name),
                'region'          =>  $faker->name,
                'city'            =>  $faker->city,
	            'address'         => $faker->address,
                'bank_name'       =>  $faker->name,
                'bank_account'    =>  $faker->name,
                'director_name'   =>  json_encode($name),
                'director_name'   =>  json_encode($name),
            ]);
	        DB::table('users')->insert([
	            'name'      => json_encode($name),
	            'status'    => "ACTIVE",
	            'type'      => "STATE",
	            'email'     => $faker->email,
	            'parent_id' => $id,
	            'password'  => bcrypt('secret'),
            ]);
            
		}
    }
}
