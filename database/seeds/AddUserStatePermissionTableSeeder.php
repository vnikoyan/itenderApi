<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddUserStatePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ["guard_name"=>"web","name"=>"user_state","icon"=>"user","code"=>"Պետական օգտատերեր"],
        ];

        foreach ($permissions as $key => $permission) {
             Permission::create(['name' => $permission["name"],'code' => $permission["code"],'icon' => $permission["icon"] ]);
        }
    }
}
