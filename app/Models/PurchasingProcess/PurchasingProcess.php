<?php

namespace App\Models\PurchasingProcess;


use App\Models\AbstractModel;

class PurchasingProcess extends AbstractModel {

    protected $table = 'purchasing_process';
//
    protected $allowed = [ "procurement_plan_id","organize_id","count","code","address","other_requirements","is_full_decide","participants","is_all_participants","deadline"];
    protected $default = [ "procurement_plan_id","organize_id","count","code","address","other_requirements","is_full_decide","participants","is_all_participants","deadline"];

    public function user(){
        return $this->hasMany('App\Models\PurchasingProcess\PurchasingProcessUser', 'purchasing_process_id', 'id');
    }
    public function procurementPlan(){
        return $this->hasOne('App\Models\Procurement\ProcurementPlan', 'id', 'procurement_plan_id')
            ->with('cpv');
    }

}
