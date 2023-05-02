<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class AddNewIconsPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();

        $new_permissions = [
            ["guard_name"=>"web","name"=>"admin","icon"=>"user-cog","code"=>"Ադմին"],
            ["guard_name"=>"web","name"=>"user","icon"=>"user","code"=>"Օգտատերեր"],
            ["guard_name"=>"web","name"=>"user_state","icon"=>"user-tie","code"=>"Պետական օգտատերեր"],
            ["guard_name"=>"web","name"=>"package","icon"=>"shopping-cart","code"=>"Փաթեթ"],
            ["guard_name"=>"web","name"=>"settings","icon"=>"cog","code"=>"Կարգավորումներ"],
            ["guard_name"=>"web","name"=>"menu","icon"=>"bars","code"=>"Մենյու"],
            ["guard_name"=>"web","name"=>"cpv","icon"=>"barcode","code"=>"Cpv"],
            ["guard_name"=>"web","name"=>"itender","icon"=>"info","code"=>"Itender"],
            ["guard_name"=>"web","name"=>"tender","icon"=>"bullhorn","code"=>"Հայտարարություններ"],
            ["guard_name"=>"web","name"=>"email","icon"=>"envelope","code"=>"Էլ․ նամակ"],
            ["guard_name"=>"web","name"=>"organizer","icon"=>"user-circle","code"=>"Պատվիրատու"],
            ["guard_name"=>"web","name"=>"bank_secure_stats ","icon"=>"shield-alt","code"=>"Երաշխիքի հաշվետվություն"],
        ];

        for ($i=0; $i < count($permissions); $i++) { 
            $permission = $permissions[$i];
            $new_permission = $new_permissions[$i];
            $permission->guard_name = $new_permission['guard_name'];
            $permission->name = $new_permission['name'];
            $permission->icon = $new_permission['icon'];
            $permission->code = $new_permission['code'];
            $permission->save();
        }

        // foreach ($permissions as $key => $permission) {
        //      Permission::create(['name' => $permission["name"],'code' => $permission["code"],'icon' => $permission["icon"] ]);
        // }
    }
}
