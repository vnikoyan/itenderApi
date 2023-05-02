<?php

use Illuminate\Database\Seeder;
use App\Models\Translation\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ["code"=>"en","name"=>"English","status"=>1],
            ["code"=>"hy","name"=>"Հայերեն", "status"=>1],
            ["code"=>"ru","name"=>"Русский","status"=>1],
        ];

        foreach ($languages as $key => $language) {
            Language::create(['name' => $language["name"],'code' => $language["code"],'status' => $language["status"] ]);
        }
    }
}
