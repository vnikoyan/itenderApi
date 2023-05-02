<?php
namespace App\Http\Controllers\Api\Organize;

use App\Repositories\Organize\OrganizeOnePersonRepository;
use App\Support\Transformers\Organize\OrganizeOnePersonTransformer;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\Organize\CreateOrganizeOnePersonRequest;
use App\Http\Requests\Organize\CreateOrganizePlanRequest;
use App\Http\Requests\Organize\UpdateOrganizeRequest;
use App\Http\Resources\Organize\OnePerson\OrganizeOnePersonResource;
use App\Http\Resources\Organize\OrganizeResource;
use App\Services\Organize\OrganizeOnePersonService;
use App\Support\Transformers\Organize\OrganizeCardOnePersonTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class OrganizeOnePersonController  extends AbstractController
{
    /**
     * Organize.
     * @var     OrganizeOnePersonRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $organize;
    /**
     * User controller constructor.
     *
     * @param OrganizeOnePersonRepository $organize
    */
    public function __construct(OrganizeOnePersonRepository $organize){
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
        // return new OrganizeOnePersonResource($organize);
        return $this->respondWithItem($organize, new OrganizeOnePersonTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getByUser(){
        $organize = $this->organize->getByProcurementByUser($this->shield->id());
        return $this->respondWithPagination($organize, new OrganizeCardOnePersonTransformer($this->shield->id()));
    }

    public function getAll(Request $request){
        $objects = $this->organize->getAll($request);
        return OrganizeResource::collection($objects);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateOrganizeOnePersonRequest $request
     * @return JsonResponse
    */
    public function store(CreateOrganizeOnePersonRequest $request){
        $service = new OrganizeOnePersonService($request);
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
        $service = new OrganizeOnePersonService($request);
        $organize = $service->updateOrganize($id);
        return $this->respondWithItem($organize, new OrganizeOnePersonTransformer($this->shield->id()));
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

    public function uploadInvoiceFile(Request $request, Int $id){
        $service = new OrganizeOnePersonService($request);
        $response = $service->uploadInvoiceFile($id);
        if(!$response){
            return $this->respondWithStatus(false);
        }
        return $this->respondWithStatus(true, $response);
    }

    public function uploadRowsFile(Request $request, Int $id){
        $service = new OrganizeOnePersonService($request);
        $response = $service->uploadRowsFile($id);
        if(!$response){
            return $this->respondWithStatus(false);
        }
        return $this->respondWithStatus(true, $response);
    }
    

    public function getRowsFile(Request $request, Int $id){
        $service = new OrganizeOnePersonService($request);
        $response = $service->uploadInvoiceFile($id);
        return $response;
        if(!$response){
            return $this->respondWithStatus(false);
        }
        return $this->respondWithStatus(true, $response);
    }
    
}
