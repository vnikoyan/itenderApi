<?php

namespace App\Http\Controllers\Api\Contract;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
use App\Repositories\Contract\ContractOrdersRepository;
use App\Http\Resources\Contract\ContractOrdersResource;
use App\Services\Contract\ContractOrdersService;


class ContractOrdersController extends AbstractController
{
    protected $contract_orders;
    /**
     * Contract Orders constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct(ContractOrdersRepository $contract_orders){
        parent::__construct();
        $this->contract_orders = $contract_orders;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function index(Request $request){
        $data = $this->contract_orders->getByClient($this->shield->id(), $request->status);
        $contracts = $this->applyFiltersForServerTable($request, $data);
        return $this->respondWithPaginationServerTable(ContractOrdersResource::collection($contracts['data']), $contracts['count']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getByProvider(Request $request){
        $data = $this->contract_orders->getByProvider($this->shield->id(), $request->status);
        $contracts = $this->applyFiltersForServerTable($request, $data);
        return $this->respondWithPaginationServerTable(ContractOrdersResource::collection($contracts['data']), $contracts['count']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $service = new ContractOrdersService($request);
        return $service->createContractOrder();
        return $this->respondWithStatus(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
    */
    public function update(Request $request, int $id){
        $service = new ContractOrdersService($request);
        $service->updateContractOrder($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Cancel Order by ID.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
    */
    public function cancel(Request $request, int $id){
        $service = new ContractOrdersService($request);
        return $service->cancelContractOrder($id);
        return $this->respondWithStatus(true);
    }
    

}
