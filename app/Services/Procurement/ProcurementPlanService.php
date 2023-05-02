<?php
namespace App\Services\Procurement;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Procurement\Procurement;
use App\Models\User\User;
use App\Models\Procurement\ProcurementPlan;
use App\Models\Procurement\ProcurementPlanDetails;
use App\Models\Settings\Units;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcurementPlanService
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
    /** * User Service Class Constructor.
     *
     * @param Request $request
     */
    public function updateBYProcurementId($procurement_id):void{
        \DB::statement(
            "UPDATE procurement_plans
                JOIN (SELECT id, organisation_id, RANK() 
                OVER ( PARTITION BY procurement_id ORDER BY id ) order_index, RANK() 
                OVER ( PARTITION BY procurement_id, cpv_id, is_condition ORDER BY id ) + 500 is_is_condition, RANK() 
                OVER ( PARTITION BY procurement_id, cpv_id, is_condition ORDER BY id ) is_not_condition 
                FROM procurement_plans ) ranks 
                ON ( ranks.id = procurement_plans.id ) 
                SET procurement_plans.order_index = ranks.order_index , procurement_plans.cpv_drop = CASE  
                WHEN procurement_plans.is_condition=1 
                THEN ranks.is_is_condition 
                ELSE ranks.is_not_condition 
                END
                WHERE procurement_plans.procurement_id =".$procurement_id);
    }

    public function createProcurementPlanr(){
        $gerupUser = User::getGerupRootUser();

        $procurement_id = Procurement::where("id",$this->request->procurement_id)->where("organisation_id",auth('api')->user()->parent_id)->get()->toArray();


        if( ((integer) $gerupUser->divisions > auth('api')->user()->divisions) || empty($procurement_id) ){
            throw new AppException(AppExceptionType::$NO_PERMISSION);
        }


        $procurementPlan = new ProcurementPlan();
        $this->bilder($procurementPlan);
    }

    public function updateDetailsProcurementPlanr($id){

            $gerupUser = User::getGerupRootUser();
            if(auth('api')->user()->divisions == $gerupUser->divisions ){
                $procurementPlanDetails = ProcurementPlanDetails::where("organisation_id",auth('api')->user()->parent_id)->findOrFail($id);
                $procurementPlanDetails->status =
                    (ProcurementPlanDetails::STATUS_APPROVE == $procurementPlanDetails->status ||
                        ProcurementPlanDetails::STATUS_APPROVE_EDIT == $procurementPlanDetails->status ) ?
                        ProcurementPlanDetails::STATUS_APPROVE_EDIT : $procurementPlanDetails->status;
            }else{
                $procurementPlanDetails = ProcurementPlanDetails::where("organisation_id",auth('api')->user()->parent_id)->findOrFail($id);
            }

          $procurementPlanDetails->count = ($this->request->count) ? $this->request->count : $procurementPlanDetails->count;
          $procurementPlanDetails->unit_amount = ($this->request->unit_amount) ? $this->request->unit_amount : $procurementPlanDetails->unit_amount;
          $procurementPlanDetails->type = ($this->request->type) ? $this->request->type : $procurementPlanDetails->type;
          $procurementPlanDetails->classifier = ($this->request->classifier) ? $this->request->classifier : $procurementPlanDetails->classifier;
          $procurementPlanDetails->financial_classifier = ($this->request->financial_classifier) ? $this->request->financial_classifier : $procurementPlanDetails->financial_classifier;
        //   $procurementPlanDetails->classifier_id = ($this->request->classifier_id) ? $this->request->classifier_id : $procurementPlanDetails->classifier_id;
        //   $procurementPlanDetails->financial_classifier_id = ($this->request->financial_classifier_id) ? $this->request->financial_classifier_id : $procurementPlanDetails->financial_classifier_id;
          $procurementPlanDetails->date = ($this->request->date) ? $this->request->date : $procurementPlanDetails->date;
          $procurementPlanDetails->out_count = ($this->request->out_count) ? $this->request->out_count : $procurementPlanDetails->out_count;
          $procurementPlanDetails->save();
    }

    public function updateProcurementPlanr($id){
        $gerupUser = User::getGerupRootUser();
        $procurement_id = Procurement::where("organisation_id",auth('api')->user()->parent_id)->get()->toArray();
        // if( ((integer) $gerupUser->divisions > auth('api')->user()->divisions) || empty($procurement_id) ){
        //     throw new AppException(AppExceptionType::$NO_PERMISSION);
        // }
        $this->edit($id,$gerupUser);
    }

    function getCpvDrop($request){
        $cpv_id = $request["cpv_id"];
        $is_condition = $request["is_condition"];
        $procurement_id = $this->request->procurement_id;
        $last_row = ProcurementPlan::where([
            ['procurement_id', $procurement_id], 
            ['is_condition', $is_condition], 
            ['cpv_id', $cpv_id]
        ])->orderBy("id", "DESC")->first();
        if($last_row){
            return $last_row->cpv_drop + 1;
        } else {
            return $is_condition ? 501 : 1;
        }
    }

    public function bilder():void{
        foreach($this->request->procurement as $key => $request){
            $unit = Units::find($request["unit"]);
            $unit_title = $unit->translations['title'];
            $procurementPlan = new ProcurementPlan();
            $procurementPlan->procurement_id  = $this->request->procurement_id;
            $procurementPlan->organisation_id  = auth('api')->user()->parent_id;
            $procurementPlan->{"user_id_".auth('api')->user()->divisions}  = auth('api')->user()->id;
            $divisions_ = auth('api')->user()->divisions-1;
            $procurementPlan->{"user_id_".$divisions_}  = $request["user_id"];
            $procurementPlan->cpv_id  = $request["cpv_id"];
            $procurementPlan->is_condition  = $request["is_condition"];
            $procurementPlan->condition_type  = $request["condition_type"];
            $procurementPlan->cpv_type  = $request["cpv_type"];
            $procurementPlan->cpv_drop  = $this->getCpvDrop($request);
            $procurementPlan->unit  = $unit_title['hy'];
            $procurementPlan->unit_ru  = $unit_title['ru'];
            $procurementPlan->specifications_id  = $request["specifications_id"];
            $procurementPlan->save();
            foreach ($request["plan_details"] as $value){
                $details = new ProcurementPlanDetails();
                $details->count                     = $value["count"];
                $details->unit_amount               = $value["unit_amount"];
                $details->type                      = $this->getPlanCpvProcedureType($request, $value);
                // $details->classifier_id             = $value["classifier_id"];
                // $details->financial_classifier_id   = $value["financial_classifier_id"];
                $details->classifier                = $value["classifier"];
                $details->financial_classifier      = $value["financial_classifier"];
                $details->date                      = $value["date"];
                $details->out_count                 = $value["out_count"];
                $details->organisation_id           = auth('api')->user()->parent_id;
                $details->procurement_plans_id      = $procurementPlan->id;
                $details->save();
            }
        }
        // $this->updateBYProcurementId($this->request->procurement_id);
    }

    public function getPlanCpvProcedureType($item, $item_details)
    {
        $group_code = substr($item["code"], 0, 3);
        $group = ProcurementPlan::where("procurement_id",$this->request->procurement_id)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select(
                    DB::raw('SUBSTR(cpv.code, 1, 3) as cpv_group'),
                    DB::raw('sum(procurement_plans_details.count * procurement_plans_details.unit_amount) as total')
                 )
                ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->groupBy("cpv_group")
                ->where(DB::raw('SUBSTR(cpv.code, 1, 3)'), $group_code)
                ->first();

        $current_total = $item_details["count"] * $item_details["unit_amount"];

        if($group){
            $total = $group->total + $current_total;
        } else {
            $total = $current_total;
        }

        // < 1 MLN -> MA
        if($total <= 1000000){
            $procedure_type = 1;
        // 1 MLN < 70 MLN -> GH
        } else if($total <= 70000000){
            $procedure_type = 6;
        // 70 MLN < -> BM
        } else {
            $procedure_type = 4;
        }

        $group_children = ProcurementPlan::where("procurement_id",$this->request->procurement_id)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->where('cpv.code', 'like', $group_code.'%')
                ->with('details')
                ->get();


        foreach ($group_children as $group_child) {
            $details = ProcurementPlanDetails::find($group_child->details[0]->procurement_plans_id);
            $details->type = $procedure_type;
            $details->save();
        }

        return $procedure_type;
    }

    public function edit($id,$gerupUser){

        if(auth('api')->user()->divisions == $gerupUser->divisions ){
            $procurementPlan = ProcurementPlan::where("organisation_id",auth('api')->user()->parent_id)->findOrFail($id);
            $procurementPlan->status =
                (ProcurementPlan::STATUS_APPROVE == $procurementPlan->status || ProcurementPlan::STATUS_APPROVE_EDIT == $procurementPlan->status ) ?
                    ProcurementPlan::STATUS_APPROVE_EDIT : $procurementPlan->status;
        }else{
            $procurementPlan = ProcurementPlan::where("organisation_id",auth('api')->user()->parent_id)
                ->where("status","!=",ProcurementPlan::STATUS_APPROVE)->findOrFail($id);
        }

        if(isset($this->request->specifications_id)){
            $procurementPlan->specifications_id   = $this->request->specifications_id;
        }
        if(isset($this->request->cpv_type)){
            $procurementPlan->cpv_type   = $this->request->cpv_type;
        }
        if(isset($this->request->is_condition)){
            $procurementPlan->is_condition  = $this->request->is_condition;
        }
        if(isset($this->request->condition_type)){
            $procurementPlan->condition_type  = $this->request->condition_type;
        }
        if(isset($this->request->unit)){
            $procurementPlan->unit   = $this->request->unit;
        }

        $procurementPlan->{"user_id_".auth('api')->user()->divisions} = auth('api')->user()->id;
        $procurementPlan->{"user_id_".(auth('api')->user()->divisions-1)} = $this->request->user_id;
        $procurementPlan->save();
        $this->updateBYProcurementId($procurementPlan->procurement_id);
        return $procurementPlan;
    }

    public function storeDetailsProcurementPlanr(int $procurement_id){
                $details = new ProcurementPlanDetails();
                $details->count                   =  $this->request->count;
                $details->unit_amount             =  $this->request->unit_amount;
                $details->type                    =  $this->request->type;
                $details->classifier_id           =  $this->request->classifier_id;
                $details->financial_classifier_id =  $this->request->financial_classifier_id;
                $details->date                    = $this->request->date;
                $details->out_count               = $this->request->out_count;
                $details->organisation_id         = auth('api')->user()->parent_id;
                $details->procurement_plans_id    =  $procurement_id;
                $details->save();
                return $details;
    }

    public function updateStatusProcurementPlanr($id)
    {
        $procurementPlan = ProcurementPlan::where("organisation_id",auth('api')->user()->parent_id)->findOrFail($id);
        $procurementPlan->status = $this->request->status;
        $procurementPlan->save();
        return $procurementPlan;
    }
    public function updateStatusDetailsProcurementPlanr($id)
    {
        $procurementPlanDetails = ProcurementPlanDetails::where("organisation_id",auth('api')->user()->parent_id)->findOrFail($id);
        $procurementPlanDetails->status = $this->request->status;
        $procurementPlanDetails->save();
        return $procurementPlanDetails;
    }

}
