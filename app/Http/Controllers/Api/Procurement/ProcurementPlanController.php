<?php
namespace App\Http\Controllers\Api\Procurement;

use App\Repositories\Procurement\ProcurementPlanRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Exceptions\AppException;
use App\Support\Transformers\Procurement\ProcurementPlan\ProcurementPlanTransformer;
use App\Support\Transformers\Procurement\ProcurementPlan\ProcurementPlanDetailsTransformer;
use App\Support\Transformers\Procurement\ProcurementPlan\ProcurementPlanByOrganizeTransformer;
use App\Http\Requests\Procurement\CreateProcurementPlanRequest;
use App\Http\Requests\Procurement\UpdateProcurementPlanRequest;
use App\Http\Requests\Procurement\StoreDetailsProcurementPlanRequest;
use App\Http\Requests\Procurement\UpdateDetailsProcurementPlanRequest;
use App\Http\Requests\Procurement\UpdateStatusProcurementPlanRequest;
use App\Services\Procurement\ProcurementPlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;


class ProcurementPlanController  extends AbstractController
{
    /**
     * Users.
     * @var     ProcurementPlanRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $procurementPlan;
    /**
     * User controller constructor.
     *
     * @param ProcurementPlanRepository $procurementPlan
    */
    public function __construct(ProcurementPlanRepository $procurementPlan ){
        parent::__construct();
        $this->procurementPlan = $procurementPlan;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function index(){
        $procurementPlan = $this->procurementPlan->getByPermissions();
        return $this->respondWithPagination($procurementPlan, new ProcurementPlanTransformer($this->shield->id()));
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getByQuery(int $procurement_id,Request $request){
        $procurementPlan =  $this->procurementPlan->getByQuery($procurement_id,$request->all());
        return $this->respondWithItems($procurementPlan, new ProcurementPlanDetailsTransformer($this->shield->id()));
    }


    public function getCpvGroup(int $procurement_id,int $type){
        return $this->procurementPlan->getByCpvGroup($procurement_id,$type);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getListByCpvGroup(int $procurement_id,int $type,int $cpv_group){
        $procurementPlan =  $this->procurementPlan->getListByCpvGroup($procurement_id,$type,$cpv_group);
        return $this->respondWithPagination($procurementPlan, new ProcurementPlanDetailsTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
    */
    public function show(int $id)
    {
        $procurementPlan = $this->procurementPlan->getByPermissions($id);
        return $this->respondWithPagination($procurementPlan, new ProcurementPlanTransformer($this->shield->id()));
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @param $type
     * @return array
     */
    public function showDataTable(int $id)
    {
        $budget_type =\Input::get("budget_type");
        return $this->procurementPlan->getByPermissionsDataTable($id,$budget_type);
    }
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function getHistories($id){
        $procurementPlan = $this->procurementPlan->retrieveById($id)->histories()->orderBy("performed_at","desc")->select("meta","performed_at")->paginate(10);
        $response = [
            'data' => $procurementPlan->items(),
            'pagination' => [
                'total' => $procurementPlan->total(),
                'count' => count($procurementPlan->items()),
                'page' => $procurementPlan->currentPage(),
                'continue' => $procurementPlan->hasMorePages()
            ]
        ];
        $response['timestamp'] = Request::server('REQUEST_TIME_FLOAT');
        return Response::json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function getHistoriesDetails($id){

        $procurementPlan = $this->procurementPlan->getHistoriesDetails($id);
        $response = [
            'data' => $procurementPlan->items(),
            'pagination' => [
                'total' => $procurementPlan->total(),
                'count' => count($procurementPlan->items()),
                'page' => $procurementPlan->currentPage(),
                'continue' => $procurementPlan->hasMorePages()
            ]
        ];
        $response['timestamp'] = Request::server('REQUEST_TIME_FLOAT');
        return Response::json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProcurementPlanRequest $request
     * @return JsonResponse
     * @throws AppException
    */
    public function store(CreateProcurementPlanRequest $request){
        $service = new ProcurementPlanService($request);
        return $service->createProcurementPlanr();
        return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateProcurementPlanRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws AppException
    */
    public function update(UpdateProcurementPlanRequest $request, int $id){
        $service = new ProcurementPlanService($request);
        $service->updateProcurementPlanr($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateProcurementPlanRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws AppException
    */
    public function editDetails(UpdateDetailsProcurementPlanRequest $request, int $id){
        $service = new ProcurementPlanService($request);
        $service->updateDetailsProcurementPlanr($id);
        return $this->respondWithStatus(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateProcurementPlanRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws AppException
    */
    public function storeDetails(StoreDetailsProcurementPlanRequest $request, int $procurement_id){
        $service = new ProcurementPlanService($request);
        $service->storeDetailsProcurementPlanr($procurement_id);
        return $this->respondWithStatus(true);
    }

    /**
     * Show the form for editing the specified resource.
     * @param UpdateStatusProcurementPlanRequest $request
     * @param int $id
     * @return JsonResponse
    */
    public function updateStatus(UpdateStatusProcurementPlanRequest $request, int $id){
        $service = new ProcurementPlanService($request);
        $service->updateStatusProcurementPlanr($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getValidType(int $id,int $cpv_id){
        return $this->procurementPlan->getValidType($id,$cpv_id);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getByFinancialClassifierId(int $procurement_id){
        return $this->procurementPlan->getByFinancialClassifierId($procurement_id);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getByFinancialClassifierCuntCpvType(int $procurement_id,int $financial_classifier){
        return $this->procurementPlan->getByFinancialClassifierCuntCpvType($procurement_id,$financial_classifier);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getByClassifierId(int $procurement_id){
        return $this->procurementPlan->getByClassifierId($procurement_id);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getByCpvType(int $procurement_id,$cpv_type){
        return $this->procurementPlan->getByCpvType($procurement_id,$cpv_type);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getByClassifiersForFinancial(int $procurement_id,int $classifier_id){
        return  $this->procurementPlan->getByClassifiersForFinancial($procurement_id,$classifier_id);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $procurement_id
     * @param int $classifier_id
     * @param int $financial_classifier
     * @param int $cpv_type
     * @return JsonResponse
    */
    public function getProcurementByClassifierIdFinancialId(int $procurement_id,int $classifier_id,int $financial_classifier,int $cpv_type){
        $procurementPlan =  $this->procurementPlan->getProcurementByClassifierIdFinancialId( $procurement_id, $classifier_id, $financial_classifier, $cpv_type);
        return $this->respondWithPagination($procurementPlan, new ProcurementPlanDetailsTransformer($this->shield->id()));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $procurement_id
     * @param int $classifier_id
     * @param int $financial_classifier
     * @return JsonResponse
    */
    public function getByClassifiersForFinancialCpvType(int $procurement_id,int $classifier_id,int $financial_classifier){
        return $this->procurementPlan->getByClassifiersForFinancialCpvType( $procurement_id, $classifier_id, $financial_classifier);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getByFinancialClassifiers(int $procurement_id,int $financial_classifier_id,int $cpv_type){
        $procurementPlan =  $this->procurementPlan->getByFinancialClassifiers($procurement_id,$financial_classifier_id,$cpv_type);
        return $this->respondWithPagination($procurementPlan, new ProcurementPlanDetailsTransformer($this->shield->id()));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getByClassifiers(int $procurement_id,int $classifier_id,int $cpv_type){
        $procurementPlan =  $this->procurementPlan->getByClassifiers($procurement_id,$classifier_id,$cpv_type);
        return $this->respondWithPagination($procurementPlan, new ProcurementPlanTransformer($this->shield->id()));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $procurement_id
     * @return JsonResponse
    */
    public function getFilePdf(int $procurement_id){
//        $procurementPlan =  $this->procurementPlan->getFilePdf($procurement_id);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.procurementPlan',[
//            "procurementPlan" => $procurementPlan
        ]);
        return $pdf->stream();
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @param int $cpv_id
     * @return JsonResponse
    */
    public function getPlanByOrganize(int $procurement_id){

        $procurementPlan =  $this->procurementPlan->getPlanByOrganize($procurement_id);

        return $this->respondWithPagination($procurementPlan, new ProcurementPlanByOrganizeTransformer($this->shield->id()));
    }


}
