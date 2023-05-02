<?php

namespace App\Http\Controllers\Api\Contract;

use App\Http\Controllers\Api\AbstractController;
use App\Repositories\Contract\ContractLotsRepository;
use App\Http\Resources\Contract\ContractLotsResource;
use Illuminate\Http\Request;

class ContractLotsController extends AbstractController
{
    protected $contract_lots;
    /**
     * Contract controller constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct(ContractLotsRepository $contract_lots){
        parent::__construct();
        $this->contract_lots = $contract_lots;
    }
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    public function index(Request $request)
    {
        $query = $request->input('query');
        if(is_null($query)){
            $query = '';
        }
        $data = $this->contract_lots->getByClient($this->shield->id(), $request->input('query'));
        $contracts = $this->applyFiltersForServerTable($request, $data);
        return $this->respondWithPaginationServerTable(ContractLotsResource::collection($contracts['data']), $contracts['count']);
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id){
        $this->contract_lots->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }

}
