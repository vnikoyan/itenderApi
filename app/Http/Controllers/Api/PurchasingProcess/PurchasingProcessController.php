<?php


namespace App\Http\Controllers\Api\PurchasingProcess;

use App\Repositories\PurchasingProcess\PurchasingProcessRepository;
use App\Http\Requests\PurchasingProcess\CreatePurchasingProcessRequest;
use App\Http\Requests\PurchasingProcess\CreatePurchasingProcessUserRequest;
use App\Http\Requests\PurchasingProcess\UpdatePurchasingProcessRequest;
use App\Services\PurchasingProcess\PurchasingProcessService;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Transformers\PurchasingProcess\PurchasingProcessTransformer;
use Illuminate\Http\JsonResponse;

class PurchasingProcessController extends AbstractController
{

    /**
     * Participant.
     * @var     PurchasingProcessRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $purchasingProcess;
    /**
     * User controller constructor.
     *
     * @param PurchasingProcessRepository $purchasingProcess
    */

    public function __construct( PurchasingProcessRepository $purchasingProcess){
        parent::__construct();
        $this->purchasingProcess = $purchasingProcess;
    }


    /**
     * Display a listing of the resource.
     * @param  int $id
     * @return JsonResponse
     */
    public function index(){
        $purchasingProcess = $this->purchasingProcess->paginate();
        return $this->respondWithItem($purchasingProcess, new PurchasingProcessTransformer($this->shield->id()));
    }

    public function notSuggestions(){
        $participant = $this->purchasingProcess->notSuggestions();
        return $this->respondWithPagination($participant, new PurchasingProcessTransformer($this->shield->id()));
    }

    public function suggestions(){
        $participant = $this->purchasingProcess->suggestions();
        return $this->respondWithPagination($participant, new PurchasingProcessTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id){
        $purchasingProcess = $this->purchasingProcess->retrieveById($id);
        return $this->respondWithItem($purchasingProcess, new PurchasingProcessTransformer($this->shield->id()));
    }

    /**
     * Display a listing of the resource.
     * @param  int  $organisation_id
     * @return JsonResponse
    */
    public function showByOrganisation(int $organisation_id){
        $participant = $this->purchasingProcess->getByOrganisationId($organisation_id);
        return $this->respondWithPagination($participant, new PurchasingProcessTransformer($this->shield->id()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePurchasingProcessRequest $request
     * @return JsonResponse
     */
    public function store(CreatePurchasingProcessRequest $request){
        $service = new PurchasingProcessService($request);
        $service->createPurchasingProcess();
        return $this->respondWithStatus(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePurchasingProcessUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function storeUser(CreatePurchasingProcessUserRequest $request,int $id){
        $service = new PurchasingProcessService($request);
        $service->crateUser($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdatePurchasingProcessRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdatePurchasingProcessRequest $request,int $id){
        $service = new PurchasingProcessService($request);
        $service->updatePurchasingProcess($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function destroy(int $id){
        $this->purchasingProcess->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteUser(int $id,int $user_id){
        $this->purchasingProcess->deleteUser($id,$user_id);
        return $this->respondWithStatus(true);
    }

}
