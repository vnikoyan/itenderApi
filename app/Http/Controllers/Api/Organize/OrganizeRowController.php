<?php
namespace App\Http\Controllers\Api\Organize;

use App\Exports\OrganizeRowsExport;
use App\Repositories\Organize\OrganizeRowRepository;
use App\Support\Transformers\Organize\OrganizeRowTransformer;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\Organize\CreateOrganizeRowRequest;
use App\Http\Requests\Organize\CreateArrayOrganizeRowRequest;
use App\Http\Requests\Organize\UpdateOrganizeRowRequest;
use App\Http\Resources\Organize\OrganizeRowFullResource;
use App\Services\Organize\OrganizeRowService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use Validator;
use Illuminate\Http\Request;

class OrganizeRowController  extends AbstractController
{
    /**
     * OrganizeRow.
     * @var     OrganizeRowRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $organizeRow;
    /**
     * User controller constructor.
     *
     * @param OrganizeRowRepository $organizeRow
    */
    public function __construct(OrganizeRowRepository $organizeRow){
        parent::__construct();
        $this->organizeRow = $organizeRow;
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function show(int $id){
        $paginator = $this->organizeRow->retrieveById($id);
        return $this->respondWithItem($paginator, new OrganizeRowTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $organize_id
     * @return JsonResponse
    */
    public function getByOrganize(int $organize_id){
        $organize_rows = $this->organizeRow->getByOrganize($organize_id);
        return OrganizeRowFullResource::collection($organize_rows);
        // return $this->respondWithItems($organize_rows, new OrganizeRowTransformer($this->shield->id()));
    }

    public function getByOrganizeParticipmants(int $organize_id){
        $organize = $this->organizeRow->getByOrganizeWithParticipmants($organize_id);
        return $organize;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateArrayOrganizeRowRequest $request
     * @return JsonResponse
    */
    public function storeArray(CreateArrayOrganizeRowRequest $request){
        $service = new OrganizeRowService($request);
        $service->createArrayOrganizeRow();
        return $this->respondWithStatus(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateArrayOrganizeRowRequest $request
     * @return JsonResponse
    */
    public function numbering(Request $request){
        $service = new OrganizeRowService($request);
        return $service->numberingOrganizeRows();
        return $this->respondWithStatus(true);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateArrayOrganizeRowRequest $request
     * @return JsonResponse
    */
    public function autoInsertPercents(Request $request){
        $service = new OrganizeRowService($request);
        return $service->autoInsertPercents();
        return $this->respondWithStatus(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateArrayOrganizeRowRequest $request
     * @return JsonResponse
    */
    public function storeArrayFromExcel(Request $request){
        $service = new OrganizeRowService($request);
        $rows = $service->createArrayOrganizeRowFromExcel();
        return $this->respondWithStatus(true, $rows);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateOrganizeRowRequest $request
     * @return JsonResponse
    */
    public function store(CreateOrganizeRowRequest $request){
        $service = new OrganizeRowService($request);
        $service->createOrganizeRow();
        return $this->respondWithStatus(true);
    }
    /**
     * Update the form for editing the specified resource.
     *
     * @param UpdateOrganizeRowRequest $request
     * @param int $id
     * @return JsonResponse
    */
    public function update(UpdateOrganizeRowRequest $request, int $id){
        $service = new OrganizeRowService($request);
        $organize = $service->updateOrganizeRow($id);
        return $this->respondWithItem($organize, new OrganizeRowTransformer($this->shield->id()));
    }
    /**
     * Update the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
    */
    public function updateInfo(Request $request, int $id){
        $service = new OrganizeRowService($request);
        $organize = $service->updateInfoOrganizeRow($id);
        return $this->respondWithItem($organize, new OrganizeRowTransformer($this->shield->id()));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function destroy(int $id){
        $this->organizeRow->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }

    public function deleteArray(Request $request)
    {
        $rows = $this->organizeRow->whereIn('id', $request)->delete();
        return $rows;
    }

    public function setWinnersForLots(Request $request)
    {
        return $request;
    }

    public function downloadFile(Request $request){
        $type = $request->get('type');
        $rowsJSON = $request->get('rows');
        $rows = json_decode($rowsJSON);
        return Excel::download(new OrganizeRowsExport($rows), "lots_excel_example.xlsx");
    }
    
}