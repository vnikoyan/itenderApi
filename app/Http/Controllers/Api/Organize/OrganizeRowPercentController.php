<?php


namespace App\Http\Controllers\Api\Organize;


use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\Organize\CreateOrganizeRowPercentRequest;
use App\Http\Requests\Organize\UpdateOrganizeRowPercentRequest;
use App\Repositories\Organize\OrganizeRowPercentRepository;
use App\Services\Organize\OrganizeRowPercentService;
use App\Support\Transformers\Organize\OrganizeRowPercentTransformer;
use Illuminate\Http\JsonResponse;

class OrganizeRowPercentController extends AbstractController
{
    /**
     * OrganizeRowPercent.
     * @var     OrganizeRowPercentRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $organizePercentRow;
    /**
     * User controller constructor.
     *
     * @param OrganizeRowPercentRepository $organizePercentRow
    */
    public function __construct(OrganizeRowPercentRepository $organizePercentRow){
        parent::__construct();
        $this->organizePercentRow = $organizePercentRow;
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function show(int $id){
        $paginator = $this->organizePercentRow->retrieveById($id);
        return $this->respondWithItem($paginator, new OrganizeRowPercentTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $organize_row_id
     * @return JsonResponse
    */
    public function organizeRow(int $organize_row_id){
        $paginator = $this->organizePercentRow->getByOrganizeRow($organize_row_id);
        return $this->respondWithPagination($paginator, new OrganizeRowPercentTransformer($this->shield->id()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateOrganizeRowPercentRequest $request
     * @return JsonResponse
    */
    public function store(CreateOrganizeRowPercentRequest $request){
        $service = new OrganizeRowPercentService($request);
        $service->createOrganizeRowPercent();
        return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param CreateOrganizeRowPercentRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateOrganizeRowPercentRequest $request, int $id){
        $service = new OrganizeRowPercentService($request);
        $organize = $service->updateOrganizeRowPercent($id);
        return $this->respondWithItem($organize, new OrganizeRowPercentTransformer($this->shield->id()));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id){
        $this->organizePercentRow->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }
}
