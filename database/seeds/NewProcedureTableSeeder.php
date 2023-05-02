<?php

use Illuminate\Database\Seeder;
use App\Models\Tender\Procedure;
class NewProcedureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedures = [
             ["contact"=>"ՓՆՄ","name"=>"ՓՆՄ","name_ru"=>"ՓՆՄ"],
             ["contact"=>"ՆԲՄ","name"=>"ՆԲՄ","name_ru"=>"ՆԲՄ"],
             ["contact"=>"ԵՄ","name"=>"ԵՄ","name_ru"=>"ԵՄ"],
             ["contact"=>"ՓՊՄ","name"=>"ՓՊՄ","name_ru"=>"ՓՊՄ"],
             ["contact"=>"ՓԳՀ","name"=>"ՓԳՀ","name_ru"=>"ՓԳՀ"],
         ];

         foreach ($procedures as $key => $procedure) {
              Procedure::create(['contact' => $procedure["contact"],'name' => $procedure["name"],'name_ru' => $procedure["name_ru"] ]);
         }
    }
}

