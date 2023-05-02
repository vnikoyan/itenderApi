<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            ["name"=>"Արագածոտնի մարզ"],
            ["name"=>"Արարատի մարզ"],
            ["name"=>"Արմավիրի մարզ"],
            ["name"=>"Գեղարքունիքի մարզ"],
            ["name"=>"Լոռու մարզ"],
            ["name"=>"Կոտայքի մարզ"],
            ["name"=>"Շիրակի մարզ"],
            ["name"=>"Սյունիքի մարզ"],
            ["name"=>"Վայոց ձորի մարզ"],
            ["name"=>"Տավուշի մարզ"],
            ["name"=>"Երևան"],
        ];

        foreach($regions as $val){
            DB::table('regions')->insert([
                'name' => $val['name'],
            ]);
        }
    }
}
