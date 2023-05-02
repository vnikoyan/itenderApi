<?php


namespace App\Models\Procurement;


use App\Models\AbstractModel;
use Panoscape\History\HasHistories;


class ProcurementPlanDetails extends AbstractModel{
    use HasHistories;

    const STATUS_ACTIVE        = 0;
    const STATUS_APPROVE       = 1;
    const STATUS_APPROVE_EDIT  = 2;
    const STATUS_DELETED       = 3;

    protected $table = 'procurement_plans_details';

    protected    $allowed = ["p_id","pl_id","cpv_id","pl_detail_id","details_type","cpv_name","cpv_code","cpv_drop"];
    protected    $default = ["p_id","pl_id","cpv_id","pl_detail_id","details_type","cpv_name","cpv_code","cpv_drop"];

    public function getModelLabel()
    {
        return $this->display_name;
    }
    public function setDefault(array $default){
        $this->default = $default;
    }
    public function setAllowed(array $allowed){
        $this->allowed = $allowed;
    }
    public function procurementPlans(){
        return $this->hasOne('App\Models\Procurement\ProcurementPlan', 'id', 'procurement_plans_id');
    }
    public function financialClassifier(){
        return $this->hasOne('App\Models\Settings\FinancialClassifier', 'id', 'financial_classifier_id');
    }
    public function classifier()
    {
        return $this->hasOne('App\Models\Settings\Classifier', 'id', 'classifier_id');
    }


}
