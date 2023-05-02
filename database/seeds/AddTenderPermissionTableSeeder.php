<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddTenderPermissionTableSeeder extends Seeder
{
/** 

     * Run the database seeds. 
     *
     * @return void 
     */
    public function run()
    {
        $permissions = [
            ["guard_name"=>"web","name"=>"tender","icon"=>"user","code"=>"Պետական Հայտարարություններ"],
        ];
        foreach ($permissions as $key => $permission) {
             Permission::create(['name' => $permission["name"],'code' => $permission["code"],'icon' => $permission["icon"] ]);
        }
    }
}
