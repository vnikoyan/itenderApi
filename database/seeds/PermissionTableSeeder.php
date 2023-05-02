<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
             ["guard_name"=>"web","name"=>"admin","icon"=>"user","code"=>"Ադմին"],
             ["guard_name"=>"web","name"=>"user","icon"=>"users","code"=>"Օգտատերեր"],
             ["guard_name"=>"web","name"=>"package","icon"=>"bar-chart-2","code"=>"Փաթեթ"],
         ];

         foreach ($permissions as $key => $permission) {
              Permission::create(['name' => $permission["name"],'code' => $permission["code"],'icon' => $permission["icon"] ]);
         }
    }
}
