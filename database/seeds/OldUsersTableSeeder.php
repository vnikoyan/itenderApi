<?php

use App\Models\User\Organisation;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OldUsersTableSeeder extends Seeder
{
    /**php artisan make:seeder UsersTableSeeder


     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::connection('mysql2')->table('users')->get();
        foreach($users as $old_user){
            $old_user_ru = DB::connection('mysql2')->table('user_ru')->where('user_id', $old_user->user_id)->first();
            if($old_user->user_type != 0){
                $users_state_organisation  = new Organisation();
                $users_state_organisation->tin = (string) ($old_user->user_hvhh || $old_user->passport_serial);
                $users_state_organisation->phone = (string) $old_user->user_phone;
                $users_state_organisation->bank_account = (string) $old_user->bank_account;
                $users_state_organisation->id_card_number = (string) $old_user->soc_qart_number;
                $users_state_organisation->passport_serial_number = (string) $old_user->passport_serial;
                $users_state_organisation->passport_given_at = (string) $old_user->passport_start;
                $users_state_organisation->passport_from = (string) $old_user->passport_who;
                $users_state_organisation->passport_valid_until = (string) $old_user->passport_end;

                // Log::info($old_user->user_name);
                
                $users_state_organisation->name = ['hy' => $old_user->user_name, 'ru' => $old_user_ru ? $old_user_ru->user_name_ru : ''];
                $users_state_organisation->company_type = ['hy' => $old_user->company_type, 'ru' => $old_user->company_type_ru];
                $users_state_organisation->region = ['hy' => $old_user->company_region, 'ru' => $old_user->company_region_ru];
                $users_state_organisation->city = ['hy' => $old_user->company_city, 'ru' => $old_user->company_city_ru];
                $users_state_organisation->address = ['hy' => $old_user->company_address, 'ru' => $old_user->company_address_ru];
                $users_state_organisation->bank_name = ['hy' => $old_user->bank_name, 'ru' => $old_user_ru ? $old_user_ru->bank_name_ru : ''];
                $users_state_organisation->director_name = ['hy' => $old_user->director_name, 'ru' => $old_user->director_name_ru];
    
                $users_state_organisation->save();
    
                $type = 'USER';
                if($old_user->user_type === 3){
                    $type = 'STATE';
                }
    
                $user                   = new User();
                $user->tin              = (string) $old_user->user_hvhh;
                $user->type             = (string) $type;
                $user->status           = "ACTIVE";
                $user->phone            = (string) $old_user->user_phone;
                $user->email            = (string) $old_user->user_email;
                $user->divisions        = 2;
                $user->is_confirmed     = 1;
                $user->password         = $old_user->user_pass;
                $user->name             = ['hy' => $old_user->user_name];
                $user->parent_id        = (integer) $users_state_organisation->id;

                $is_multiuser = false;
                $user_email_accounts = User::where('email', $user->email)->get();
                if(count($user_email_accounts) >= 1) {
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
}
