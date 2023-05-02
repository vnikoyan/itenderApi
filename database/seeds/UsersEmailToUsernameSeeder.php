<?php

use App\Models\User\Organisation;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersEmailToUsernameSeeder extends Seeder
{
    /**php artisan make:seeder UsersTableSeeder


     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach($users as $user){
            $is_multiuser = false;
            $user_email_accounts = User::where('email', $user->email)->get();
            if(count($user_email_accounts) > 1) {
                $is_multiuser = true;
            }
            if($is_multiuser){
                if($user->email === '----------'){
                    $user->username = $user->tin;
                } else {
                    $user->username = $user->tin.'-'.$user->email;
                }
            } else {
                $user->username = $user->email;
            }
            $user->save();
        }
    }
}
