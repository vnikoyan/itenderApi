<?php


namespace App\Models\PurchasingProcess;


use App\Models\AbstractModel;

class PurchasingProcessParent extends AbstractModel {
    protected $table = 'purchasing_process_parent';

    protected $allowed = ["title","count","code","address","other_requirements","is_full_decide","participants","is_all_participants","deadline"];
    protected $default = ["title","count","code","address","other_requirements","is_full_decide","participants","is_all_participants","deadline"];


    public function process(){
        return $this->hasMany('App\Models\PurchasingProcess\PurchasingProcess', 'purchasing_process_parent_id', 'id');
    }
    public function processAll(){
        return $this->hasMany('App\Models\PurchasingProcess\PurchasingProcess', 'purchasing_process_parent_id', 'id')
            ->with('procurementPlan');
    }

}
