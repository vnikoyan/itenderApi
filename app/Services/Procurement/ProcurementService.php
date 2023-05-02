<?php
namespace App\Services\Procurement;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use App\Models\Procurement\Procurement;
use App\Models\Procurement\ProcurementPlan;
use App\Models\Procurement\ProcurementPlanDetails;
use App\Models\User\User;

class ProcurementService
{
	use DispatchesJobs;

	/**
	 * Incoming HTTP Request.
	 *
	 * @var \Illuminate\Http\Request;
	 */
	protected $request;

	/**
	 * User Service Class Constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request){
		$this->request = $request;
	}

    public function createProcurement(){
        $this->getAccuses();
		$procurement = new Procurement();
        if($this->request->name){
            $procurement->name = $this->request->name;
        }else{
            $procurement->name = $this->request->year;
		}

        $procurement->year = $this->request->year;
        $procurement->organisation_id = auth('api')->user()->parent_id;
        $procurement->save();

		return $procurement;
    }

    public function  approveProcurement(int $id){
        $this->getAccuses();


         $procurement = Procurement::select("procurement_plans_details.id")
         ->where("procurements.organisation_id",auth('api')->user()->parent_id)->where("procurements.id",$id)
         ->where("procurement_plans_details.status",ProcurementPlanDetails::STATUS_ACTIVE)
         ->join('procurement_plans', 'procurement_plans.procurement_id', '=', 'procurements.id')
         ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
         ->first();

          $procurement_up = Procurement::where("organisation_id",auth('api')->user()->parent_id)->where("id",$id)->first();

        if(empty($procurement) && $procurement_up){
            $procurement_up->status = Procurement::STATUS_APPROVE;
            $procurement_up->save();
            $procurement_up->plan()->update([
                'status' => ProcurementPlan::STATUS_APPROVE
            ]);
            return ["status"=>true,"plan"=>[]];
        }
        try {
            return ["status"=>false,"plan"=>$procurement->plan];
        } catch (\Exception $e) {
            throw new AppException(AppExceptionType::$NO_PERMISSION);
        }
    }

    function getAccuses():void{
        $groupUser = User::where("parent_id",auth('api')->user()->parent_id)->orderBy('divisions', 'DESC')->select("divisions")->get()->toArray();
        if( (integer) $groupUser[0]['divisions'] > auth('api')->user()->divisions ){
            throw new AppException(AppExceptionType::$NO_PERMISSION);
        }
    }
}
