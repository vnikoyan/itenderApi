<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItenderTerms extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('itender_terms')->insert([
            'min' => 2,
            'max' => 2,
        ]);
    }

}
