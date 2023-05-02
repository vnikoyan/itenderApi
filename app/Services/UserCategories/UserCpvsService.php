<?php


namespace App\Services\UserCategories;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\UserCategories\UserCpvs;

use Exception;

class UserCpvsService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
     */
    protected $request;
    /** * User Service Class Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function storeUserCpvs($user_id){
        UserCpvs::where('user_id', $user_id)->delete();
        $cpvs = $this->request->all();
        return $this->builder($cpvs, $user_id);
    }

    private function builder($cpvs, $user_id) {
        $insertArrayCpvs = [];
        foreach ($cpvs as $key => $cpv) {
            $insertArrayCpvs[$key] = [
                "cpv_id" => $cpv['id'],
                "user_id" => $user_id,
            ];
        }
        UserCpvs::insert($insertArrayCpvs);
        return true;
    }

}
