<?php

namespace App\Http\Controllers\Api\Contract;

use App\Http\Controllers\Api\AbstractController;
use App\Services\Contract\ContractsService;
use App\Support\Transformers\Contract\ContractsTransformer;
use App\Repositories\Contract\ContractsRepository;
use App\Http\Resources\Contract\ContractsResource;
use Illuminate\Http\Request;

class ContractsController extends AbstractController
{
    protected $contract;
    /**
     * Contract controller constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct(ContractsRepository $contract){
        parent::__construct();
        $this->contract = $contract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contract = $this->contract->paginate();
        return $this->respondWithPagination($contract, new ContractsTransformer($this->shield->id()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = new ContractsService($request);
        return $service->createContract();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function getByOrganize(int $id)
    {
        $contract = $this->contract->getByOrganizeId($id);
        return $contract;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getByClient(Request $request){
        ini_set('memory_limit', '-1');
        $contracts = $this->contract->getByClient($this->shield->id(), $request->input('query'));
        return $this->respondWithPaginationServerTable(ContractsResource::collection($contracts['data']), $contracts['count']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getByProvider(Request $request){
        ini_set('memory_limit', '-1');
        $contracts = $this->contract->getByProvider($this->shield->id(), $request->input('query'));
        return $this->respondWithPaginationServerTable(ContractsResource::collection($contracts['data']), $contracts['count']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getRequestsByProvider(Request $request){
        $data = $this->contract->getRequestsByProvider($this->shield->id(), $request->input('query'));
        $contracts = $this->applyFiltersForServerTable($request, $data);
        return $this->respondWithPaginationServerTable(ContractsResource::collection($contracts['data']), $contracts['count']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function fromApplication(Request $request){
        $service = new ContractsService($request);
        return $service->fromApplication();
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function fromApplicationComplete(Request $request){
        $service = new ContractsService($request);
        return $service->fromApplicationComplete();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $service = new ContractsService($request);
        $service->updateContract($id);
        return $this->respondWithStatus(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->contract->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }
}
