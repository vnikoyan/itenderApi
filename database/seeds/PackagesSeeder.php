<?php

use Illuminate\Database\Seeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            'name'    => "Անվճար",
            'price_1'  => 49000,
            'price_3'  => 79000,
            'price_6'  => 79000,
            'price_12' => 89000,
        ]);

        DB::table('packages')->insert([
            'name'    => "Էկոնոմ",
            'price_1'  => 49000,
            'price_3'  => 79000,
            'price_6'  => 79000,
            'price_12' => 89000,
        ]);

        DB::table('packages')->insert([
            'name'    => "Պրեմիում",
            'price_1'  => 99000,
            'price_3'  => 609000,
            'price_6'  => 890000,
            'price_12' => 990000,
        ]);
        
        DB::table('packages')->insert([
            'name'    => "Գոլդ",
            'price_1'  => 69000,
            'price_3'  => 109000,
            'price_6'  => 209000,
            'price_12' => 279000,
        ]);
    }
}
