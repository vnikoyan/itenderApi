<?php

use App\Models\Cpv\Cpv;
use App\Models\User\User;
use App\Models\UserCategories\UserCpvs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AddCpvParentsForUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_cpvs = UserCpvs::all();
        foreach ($user_cpvs as $user_cpv) {
            $user = User::find($user_cpv->user_id);
            $cpv = Cpv::find($user_cpv->cpv_id);
            if($user){
                $cpvsArray = [];
                $cpv->getParents($cpvsArray);
                $cpv->getChildren($cpvsArray);
                $cpvsArray = array_values(array_unique($cpvsArray, SORT_REGULAR));
                foreach ($cpvsArray as $cpv) {
                    $hasCurrentCpv = (boolean) UserCpvs::where([
                        ['user_id', $user->id],
                        ['cpv_id', $cpv],
                    ])->first();

                    if(!$hasCurrentCpv){
                        $newCpv = new UserCpvs();
                        $newCpv->user_id = $user->id;
                        $newCpv->cpv_id = $cpv;
                        $newCpv->save();
                    }
                }
            }
        }
    }
}
