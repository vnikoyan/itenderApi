<?php

namespace App\Models\Procurement;


use App\Models\AbstractModel;
use Panoscape\History\HasHistories;

class ProcurementPlan extends AbstractModel{
    use HasHistories;

    protected $table = 'procurement_plans';
//    protected $allowed = ["user","order_index","details","name","cpv",'is_condition','status',"organisation","specifications","procurement","date","cpv_drop","cpv_type"];
    protected $allowed = [
        "plan_row_id","cpv_id","cpv_name","cpv_code","cpv_drop","cpv_type","specifications_id","is_condition","condition_type","unit","unit_ru","count","organize_count","date","unit_amount","type","classifier_id","financial_classifier_id","classifier","financial_classifier","status","order_index","user","out_count"
    ];
    protected $default = [
        "plan_row_id","cpv_id","cpv_name","cpv_code","cpv_drop","cpv_type","specifications_id","is_condition","condition_type","unit","unit_ru","count","organize_count","date","unit_amount","type","classifier_id","financial_classifier_id","classifier","financial_classifier","status","order_index","user","out_count"
    ];

    const STATUS_ACTIVE        = 0;
    const STATUS_APPROVE       = 1;
    const STATUS_APPROVE_EDIT  = 2;
    const STATUS_DELETET       = 3;

//    const DELETET_STATUS = 1;


    public function getDefault()
    {
        return $this->default;
    }
    public function setDefault(array $default)
    {
         $this->default = $default;
    }

    public function getFillable()
    {
        return $this->fillable;
    }
    public function setFillable(array $fillable)
    {
         $this->fillable = $fillable;
    }


    public function setAllowed(array $allowed)
    {
         $this->allowed = $allowed;
    }
    public function getAllowed()
    {
        return $this->allowed;
    }

    public function getModelLabel()
    {
        return $this->display_name;
    }
    public function cpv(){
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv_id')
        ->with('classifier');
    }
    public function cpvOutside(){
        return $this->hasOne('App\Models\Cpv\CpvOutside', 'id', 'cpv_outside_id');
    }
    public function cpvParent(){
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv_id')
        ->with('parent');
    }
    public function cpvOne(){
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv_id');
    }
    public function organisation(){
        return $this->hasOne('App\Models\User\Organisation', 'id', 'organisation_id');
    }
    public function specifications(){
        return $this->hasOne('App\Models\Cpv\Specifications', 'id', 'specifications_id');
    }
    public function unit(){
        return $this->hasOne('App\Models\Settings\Units', 'id', 'unit');
    }
    public function details(){
         return $this->hasMany('App\Models\Procurement\ProcurementPlanDetails', 'procurement_plans_id', 'id');
    }
    public function detailsHistories(){
         return $this->hasMany('App\Models\Procurement\ProcurementPlanDetails', 'procurement_plans_id', 'id');
    }
    public function detailsFinancialClassifier(){
         return $this->hasMany('App\Models\Procurement\ProcurementPlanDetails', 'procurement_plans_id', 'id')
             ->with("financialClassifier");
    }
    public function procurement(){
        return $this->hasOne('App\Models\Procurement\Procurement', 'id', 'procurement_id');
    }




}
