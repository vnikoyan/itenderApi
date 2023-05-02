<?php

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;

use App\Models\AbstractModel;

class Procurement extends AbstractModel{

    protected $table = 'procurements';

    protected $allowed = [ "name","year","status"];
    protected $default = [ "name","year","status"];

    const STATUS_ACTIVE        = 0;
    const STATUS_APPROVE       = 1;

//

    public function organize(){
        return $this->hasMany('App\Models\Organize\Organize', 'procurement_id', 'id');
    }

    public function plan(){
        return $this->hasMany('App\Models\Procurement\ProcurementPlan', 'procurement_id', 'id');
    }

    public function planRows(){
        return $this->hasMany('App\Models\Procurement\ProcurementPlan', 'procurement_id', 'id')->with('details')->with('cpv');
    }

    public function organisation(){
        return $this->hasOne('App\Models\User\Organisation', "id", "organisation_id");
    }

}
