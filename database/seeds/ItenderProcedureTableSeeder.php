<?php

use Illuminate\Database\Seeder;
use App\Models\Tender\Procedure;

class ItenderProcedureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedures = [
             ["contact"=>"ՓԳ","name"=>"փակ գներով"],
             ["contact"=>"ԲԱ","name"=>"բաց աճուրդ"],
             ["contact"=>"ԼԱ","name"=>"լավագույն առաջարկ"],
         ];
    }
}

