<?php
namespace App\Http\Controllers\Api\Organize;

use App\Repositories\Organize\OrganizeRepository;
use App\Support\Transformers\Organize\OrganizeTransformer;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\Organize\CreateOrganizeRequest;
use App\Http\Requests\Organize\CreateOrganizePlanRequest;
use App\Http\Requests\Organize\UpdateOrganizeRequest;
use App\Http\Resources\Organize\OrganizeResource;
use App\Services\Organize\OrganizeService;
use App\Support\Transformers\Organize\OrganizeCardTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class OrganizeController  extends AbstractController
{
    /**
     * Organize.
     * @var     OrganizeRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $organize;
    /**
     * User controller constructor.
     *
     * @param OrganizeRepository $organize
    */
    public function __construct(OrganizeRepository $organize){
        parent::__construct();
        $this->organize = $organize;
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function show(int $id){
        $organize = $this->organize->retrieveById($id);
        return $this->respondWithItem($organize, new OrganizeTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getByUser(){
        $organize = $this->organize->getByProcurementByUser($this->shield->id());
        return $this->respondWithPagination($organize, new OrganizeCardTransformer($this->shield->id()));
    }

    public function getAll(Request $request){
        $organizes = $this->organize->getAll($request);
        return $this->respondWithPaginationServerTable(OrganizeResource::collection($organizes['data']), $organizes['count']);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateOrganizeRequest $request
     * @return JsonResponse
    */
    public function store(CreateOrganizeRequest $request){
        $service = new OrganizeService($request);
        $id = $service->createOrganize();
        return $this->respondWithStatus(true,["id" => $id]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateOrganizeRequest $request
     * @param int $id
     * @return JsonResponse
    */
    public function update(UpdateOrganizeRequest $request, int $id){
         $service = new OrganizeService($request);
         $organize = $service->updateOrganize($id);
         return $this->respondWithItem($organize, new OrganizeTransformer($this->shield->id()));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function destroy(int $id){
        $this->organize->cancel($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Cancel the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function cancel(int $id){
        $this->organize->cancel($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function getAllData(int $id){
        $organize = $this->organize->getAllData($id);
        return json_encode($organize->toArray());
    }

    public function contractFile(Request $request){
        return($request);
    }
}
