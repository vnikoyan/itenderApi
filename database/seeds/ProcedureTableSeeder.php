<?php

use Illuminate\Database\Seeder;
use App\Models\Tender\Procedure;
class ProcedureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedures = [
             ["contact"=>"ՄԱ","name"=>"մեկ անձից գնում","name_ru"=>"закупка у одного лица"],
             ["contact"=>"ՄԱ*","name"=>"մեկ անձից գնում*","name_ru"=>"закупка у одного лица*"],
             ["contact"=>"ՀՄԱ","name"=>"հրատապության հիմքով պայմանավորված մեկ անձից գնում","name_ru"=>"закупка у одного лица, обусловленная безотлагательностью"],
             ["contact"=>"ԲՄ","name"=>"բաց մրցույթ","name_ru"=>"открытый конкурс"],
             ["contact"=>"ՀԲՄ","name"=>"հրատապ բաց մրցույթ","name_ru"=>"открытый конкурс, обусловленная безотлагательностью"],
             ["contact"=>"ԳՀ","name"=>"գնանշման հարցում","name_ru"=>"запрос котировок"],
             ["contact"=>"ԷԱՃ","name"=>"էլեկտրոնային աճուրդ","name_ru"=>"электронный аукцион"],
         ];

         foreach ($procedures as $key => $procedure) {
              Procedure::create(['contact' => $procedure["contact"],'name' => $procedure["name"],'name_ru' => $procedure["name_ru"] ]);
         }
    }
}

