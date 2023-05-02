<?php
namespace App\Services\Organize;

// Include any required classes, interfaces etc...

use App\Models\Cpv\CpvOutside;
use App\Models\Organize\OrganizeRowPercent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Organize\OrganizeRow;
use App\Models\Organize\OrganizeRowExcel;
use App\Models\Procurement\ProcurementPlanDetails;
use App\Models\Procurement\ProcurementPlan;
use App\Models\Procurement\Procurement;
use App\Models\Cpv\Specifications;
use Illuminate\Support\Facades\Log;

class OrganizeRowService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
    */
    protected  $request;

    /**
     *
     * @param Request $request
    */
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function createOrganizeRow(){
        $organize = new OrganizeRow();
//        return $this->builder($organize);
    }


    public function createArrayOrganizeRow(){
        $array = [];
        foreach ($this->request->organize_row as $key => $value){
            $last_row = OrganizeRow::where("organize_id", $value["organize_id"])->orderBy('id', 'desc')->first();
            $array[] = [
                "procurement_plan_id" => $value["procurement_plan_id"],
                "plan_details_id"     => $value["plan_details_id"],
                "organize_id"         => $value["organize_id"],
                "count"               => $value["count"],
                "view_id"             => $last_row ? $last_row->view_id + $key + 1 : $key + 1,
                "created_at"          => date("Y-m-d H:i:s"),
            ];
            //  $array_update;
        }
        OrganizeRow::insert($array);

        foreach($this->request->organize_row as $key => $value){
              $organize = ProcurementPlanDetails::find($value["plan_details_id"]);
              $organize->organize_count = $organize->organize_count + $value["count"];
              $organize->save();
        }

    }

    public function numberingOrganizeRows(){
        $rows = $this->request->all();
        foreach ($rows as $key => $row) {
            $currentRow = OrganizeRow::find($row['id']);
            $currentRow->view_id = $key+1;
            $currentRow->save();
        }
    }

    public function autoInsertPercents(){
        $firstRow = OrganizeRow::find($this->request->id);
        $allRows = OrganizeRow::where('organize_id', $firstRow->organize_id)->get();

        $percent = [];
        if(!empty($this->request["percent"])){
            $percent = $this->request["percent"];
        }
        foreach ($allRows as $row) {
            if(!empty($percent)){
                $organizeRowPercent =  OrganizeRowPercent::where("organize_row_id",$row->id)->first();
                if(empty($organizeRowPercent)){
                    $organizeRowPercent =  new OrganizeRowPercent();
                    $organizeRowPercent->organize_row_id = $row->id;
                }
                $organizeRowPercent->name    = $percent["name"];
                $organizeRowPercent->month_1 = $percent["month_1"];
                $organizeRowPercent->month_2 = $percent["month_2"];
                $organizeRowPercent->month_3 = $percent["month_3"];
                $organizeRowPercent->month_4 = $percent["month_4"];
                $organizeRowPercent->month_5 = $percent["month_5"];
                $organizeRowPercent->month_6 = $percent["month_6"];
                $organizeRowPercent->month_7 = $percent["month_7"];
                $organizeRowPercent->month_8 = $percent["month_8"];
                $organizeRowPercent->month_9 = $percent["month_9"];
                $organizeRowPercent->month_10 = $percent["month_10"];
                $organizeRowPercent->month_11 = $percent["month_11"];
                $organizeRowPercent->month_12 = $percent["month_12"];
                $organizeRowPercent->save();
            }
        }
    }

    public function createArrayOrganizeRowFromExcel(){
        $array = [];
        foreach ($this->request->data as $key => $value){
            $cpv = new CpvOutside();
            $cpv->code = $value['cpvCode'];
            $cpv->name = $value['cpvNameArm'];
            $cpv->name_ru = $value['cpvNameRu'];
            $cpv->unit = $value['unit'];
            $cpv->unit_ru = $value['unitRu'];
            $cpv->save();
            $cpv_outside_id = $cpv->id;

            $cpv_id = $value["cpvId"];
            if(isset($value['specifications_id'])){
                $specifications_id = $value['specifications_id'];
            } else {
                $specification = new Specifications();
                $specification->description = ["hy" => $value['specification'], "ru" => $value['specificationRu']];
                $specification->users_id = auth('api')->user()->id;
                $specification->cpv_id = $cpv_id;
                $specification->save();
                $specifications_id = $specification->id;
            }

            $procurement_plan = new ProcurementPlan();
            $procurement_plan->is_from_outside = true;
            $procurement_plan->cpv_outside_id = $cpv_outside_id;
            $procurement_plan->cpv_id = $cpv_id;
            $procurement_plan->cpv_drop = $value['cpvDrop'];
            $procurement_plan->unit = $value['unit'];
            $procurement_plan->specifications_id = $specifications_id;
            $procurement_plan->is_condition = $value['is_condition'];
            $procurement_plan->condition_type = isset($value['condition_type']) ? $value['condition_type'] : 'first_point';
            $procurement_plan->save();
            $procurement_plan_id = $procurement_plan->id;

            $procurement_plan_details = new ProcurementPlanDetails();
            $procurement_plan_details->budget_type = 0;
            $procurement_plan_details->count = $value['count'];
            $procurement_plan_details->unit_amount = $value['unit_amount'];
            $procurement_plan_details->type = $value['type'];
            $procurement_plan_details->financial_classifier_id = 1;
            $procurement_plan_details->procurement_plans_id = $procurement_plan_id;
            $procurement_plan_details->save();
            $procurement_plan_details_id = $procurement_plan_details->id;

            $organize = new OrganizeRow();
            $organize->organize_id = $value["organize_id"];
            $organize->view_id = $value["lotNumber"];
            $organize->procurement_plan_id = $procurement_plan_id;
            $organize->plan_details_id = $procurement_plan_details_id;
            $organize->count = $value['count'];
            $organize->is_from_outside = true;
            $organize->save();
            $organize_id = $organize->id;
            $array[] = $organize;
        }
        return $array;
    }
    /**
     * @param $id
     * @return OrganizeRow
    */
    public function updateOrganizeRow($id):OrganizeRow{
        $organize = OrganizeRow::with('procurementPlan')->findOrFail($id);
        return $this->builder($organize);
    }
    /**
     * @param $id
     * @return OrganizeRow
    */
    public function updateInfoOrganizeRow($id){
        $organize_row = OrganizeRow::with('procurementPlan')->findOrFail($id);
        $procurement_plan = ProcurementPlan::findOrFail($organize_row->procurement_plan_id);
        $procurement_plan_details = ProcurementPlanDetails::findOrFail($organize_row->plan_details_id);
        if($procurement_plan->cpv_outside_id){
            $cpv_outside = CpvOutside::findOrFail($procurement_plan->cpv_outside_id);
            $data = $this->request->all();
            $organize_row->count = +$data['plannedCount'];
            if(isset($data['isMainTool'])){
                $organize_row->is_main_tool = $data['isMainTool'];
            }
            if(isset($data['isProductInfo'])){
                $organize_row->is_product_info = $data['isProductInfo'];
            }
            $procurement_plan->unit = $data['unit'];
            $procurement_plan->unit_amount = $data['unitAmount'];
            $procurement_plan_details->unit_amount = $data['unitAmount'];
            $procurement_plan_details->count = +$data['plannedCount'];
            $cpv_outside->unit = $data['unit'];
            $cpv_outside->unit_ru = $data['unitRu'];
            $cpv_outside->name = $data['cpvName'];
            $cpv_outside->name_ru = $data['cpvNameRu'];
            $organize_row->save();
            $procurement_plan->save();
            $procurement_plan_details->save();
            $cpv_outside->save();
        } else {
            $cpv_outside = new CpvOutside();
            $data = $this->request->all();
            $organize_row->count = +$data['plannedCount'];
            if(isset($data['isMainTool'])){
                $organize_row->is_main_tool = $data['isMainTool'];
            }
            if(isset($data['isProductInfo'])){
                $organize_row->is_main_tool = $data['isProductInfo'];
            }
            $organize_row->is_from_outside = 1;
            $procurement_plan->is_from_outside = 1;
            $procurement_plan->unit = $data['unit'];
            $procurement_plan->unit_amount = $data['unitAmount'];
            $procurement_plan_details->unit_amount = $data['unitAmount'];
            $procurement_plan_details->count = +$data['plannedCount'];
            $cpv_outside->unit = $data['unit'];
            $cpv_outside->unit_ru = $data['unitRu'];
            $cpv_outside->name = $data['cpvName'];
            $cpv_outside->name_ru = $data['cpvNameRu'];
            $cpv_outside->code = $organize_row->cpv->code;
            $cpv_outside->save();
            $procurement_plan->cpv_outside_id = $cpv_outside->id;
            $organize_row->save();
            $procurement_plan->save();
            $procurement_plan_details->save();
        }

        return $organize_row;
    }
    /**
     * @param OrganizeRow $organize
     * @return OrganizeRow
    */
    private function builder($organize) {

        $date = $this->request->all();
        $percent = [];
        if(!empty($date["percent"])){
            $percent = $date["percent"];
            unset($date["percent"]);
        }
        $countInput = 0;
        if(!empty($date["count"])){
            $countInput = $date["count"];
        }
        $countOut = $organize->count;
        foreach ($date as $key => $value){
            try {
               if($key === 'winner_lot_cpv_name'){
                    if($organize->is_from_outside){
                        $cpv_outside = CpvOutside::find($organize->procurementPlan->cpv_outside_id);
                        $cpv_outside->name = $date["winner_lot_cpv_name"];
                        $cpv_outside->save();
                    } else {
                        $cpv_outside = new CpvOutside();
                        $cpv_outside->code = $organize->cpv->code;
                        $cpv_outside->name = $date["winner_lot_cpv_name"];
                        $cpv_outside->name_ru = $organize->cpv->name_ru;
                        $cpv_outside->unit = $organize->cpv->unit;
                        $cpv_outside->unit_ru = $organize->cpv->unit_ru;
                        $cpv_outside->save();
                        $procurement_plan = ProcurementPlan::find($organize->procurement_plan_id);
                        $procurement_plan->cpv_outside_id = $cpv_outside->id;
                        $procurement_plan->is_from_outside = true;
                        $procurement_plan->save();
                        $organize->is_from_outside = true;
                    }
                } else {
                   $organize->{$key} = $value;
               }
           } catch (Exception $e) {
               return false;
           }
       }
       $organize->save();

       if(!empty($percent)){
           $organizeRowPercent =  OrganizeRowPercent::where("organize_row_id",$organize->id)->first();
            if(empty($organizeRowPercent)){
              $organizeRowPercent =  new OrganizeRowPercent();
              $organizeRowPercent->organize_row_id = $organize->id;
            }
            $organizeRowPercent->name    = $percent["name"];
            $organizeRowPercent->month_1 = $percent["month_1"];
            $organizeRowPercent->month_2 = $percent["month_2"];
            $organizeRowPercent->month_3 = $percent["month_3"];
            $organizeRowPercent->month_4 = $percent["month_4"];
            $organizeRowPercent->month_5 = $percent["month_5"];
            $organizeRowPercent->month_6 = $percent["month_6"];
            $organizeRowPercent->month_7 = $percent["month_7"];
            $organizeRowPercent->month_8 = $percent["month_8"];
            $organizeRowPercent->month_9 = $percent["month_9"];
            $organizeRowPercent->month_10 = $percent["month_10"];
            $organizeRowPercent->month_11 = $percent["month_11"];
            $organizeRowPercent->month_12 = $percent["month_12"];
            $organizeRowPercent->save();
       }
       if(!empty($date["count"])){
          $procurement = ProcurementPlanDetails::find($organize->plan_details_id);
          $procurement->organize_count = ($procurement->organize_count - $countOut) + $countInput;
          $procurement->save();
       }
       return $organize;
    }

}
