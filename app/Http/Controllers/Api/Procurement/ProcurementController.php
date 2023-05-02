<?php
namespace App\Http\Controllers\Api\Procurement;

use App\Imports\ProcurementPlanImport;
use App\Repositories\Procurement\ProcurementRepository;
use App\Support\Transformers\Procurement\ProcurementTransformer;

use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\Procurement\CreateProcurementRequest;
use App\Http\Requests\Procurement\ProcurementUploadFileRequest;
use App\Http\Requests\Procurement\ApproveProcurementRequest;
use App\Models\Procurement\Procurement;
use Validator;
use App\Models\Procurement\ProcurementPlan;
use App\Models\Procurement\ProcurementPlanDetails;
use App\Services\Procurement\ProcurementService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PlanExport;
use Illuminate\Http\Request;

class ProcurementController  extends AbstractController
{
    /**
     * Procurement.
     *
     * @var     ProcurementRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $procurement;

    /**
     * User controller constructor.
     *
     * @param ProcurementReposit    ory $procurement
     */
    public function __construct( ProcurementRepository $procurement ){
        parent::__construct();

        $this->procurement = $procurement;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $procurement = $this->procurement->getByOrganisationId($this->shield->parent_id);
        return $this->respondWithPagination($procurement, new ProcurementTransformer($this->shield->id()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProcurementRequest $request){
        $service = new ProcurementService($request);
        // unique:mileages,date,NULL,id,user_id
        $planService = $service->createProcurement();
        return $this->respondWithStatus(true, [
            'id' => $planService->id
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(ItenderStoreAndUpdateRequest $request,$id){
        // return redirect("/admin/itender");
    }
    /**
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
    */
    public  function approve(int $id,ApproveProcurementRequest $request){
        $service = new ProcurementService($request);
        $approve =  $service->approveProcurement($id);
        return $this->respondWithStatus($approve['status'], [
             $approve['plan']
        ]);
    }
    
        /**
     * Show the XLXS file for plan.
     * @param int $procurement_id
     * @return \Illuminate\Http\JsonResponse
    */
    public function downloadFile(int $id, Request $request){
        $type = $request->get('type');
        if($type === 'XLSX') {
            return Excel::download(new PlanExport($id), "procurement-plan-$id.xlsx");
        } elseif($type === 'PDF') {
            return Excel::download(new PlanExport($id), "procurement-plan-$id.pdf" , \Maatwebsite\Excel\Excel::MPDF);
        }
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $procurement_id
     * @return \Illuminate\Http\JsonResponse
    */
    public function uploadFile(int $id,ProcurementUploadFileRequest $request){
        // toDO
		$rows = \Excel::toArray(new ProcurementPlanImport, $request->file);

		unset($rows[0][0]);

         foreach ($rows[0] as $key => $value){
            $cpv = explode("/", $value[1]);
            $cpv_code = $cpv[0];
            $cpv_drop = $cpv[1];
            $is_condition = ($cpv_drop > 500 ) ? 1 : 0;
            $classifier_name = str_replace("- ","",$value[3]);

            switch ($value[4]) {
                case 'Մեկ անձ':
                    $type = 1;
                    break;
                case 'Մեկ անձ*':
                    $type = 2;
                    break;
                case 'ՀՄԱ':
                    $type = 3;
                    break;
                case 'Բաց մրցույթ':
                    $type = 4;
                    break;
                case 'Հրատապ բաց մրցույթ':
                    $type = 5;
                    break;
                case 'Գնանշման հարցում':
                    $type = 6;
                    break;
                case 'Էլեկտրոնային աճուրդ':
                    $type = 7;
                    break;
                default:
                    $type = 1;
            }

            $unit = $value[5];
            $unit_amount = $value[6];
            $count = $value[7];
            $cpv = \DB::table('cpv')->select("id","type")->where("code",$cpv_code)->first();
            if($cpv){
                $procurementPlan = new ProcurementPlan();
                $procurementPlan->cpv_id = $cpv->id;
                $procurementPlan->cpv_type = $cpv->type;
                $procurementPlan->cpv_drop = $cpv_drop;
                $procurementPlan->organisation_id = auth('api')->user()->parent_id;
                $procurementPlan->unit = $unit;
                $procurementPlan->is_condition = $is_condition;
                $procurementPlan->procurement_id = $id;
                $procurementPlan->{"user_id_".auth('api')->user()->divisions}  = auth('api')->user()->id;
                $divisions_ = auth('api')->user()->divisions-1;
                $procurementPlan->{"user_id_".$divisions_}  = $request["user_id"];
                $procurementPlan->save();
                $classifier = \DB::table('classifier')->select("id")->where("title",$classifier_name)->first();

                $date[$key] = [
                    "classifier_id"           =>  ($classifier) ? $classifier->id : "",
                    "financial_classifier_id" => 0,
                    "unit_amount"             => $unit_amount,
                    "type"                    => $type,
                    "count"                   => $count,
                    "procurement_plans_id"    => $procurementPlan->id,
                    "organisation_id"         => auth('api')->user()->parent_id,
                ];

             }
        }
        ProcurementPlanDetails::insert($date);
        return $this->respondWithStatus(true, []);

    }

    /**
     * Remove the specified plan from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id){
        Procurement::findOrFail($id)->delete();
        return response()->json(['status' => true]);
    }

}
