<?php

namespace App\Models\UserCategories;

use App\Models\AbstractModel;

class UserCpvs extends AbstractModel
{
    protected $table = 'user_cpvs';

    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }

    public function cpv(){
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv_id')->with('cpvStatistics')->with('tenderStateRow');
    }

}
