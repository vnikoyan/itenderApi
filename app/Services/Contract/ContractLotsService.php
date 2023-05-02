<?php


namespace App\Services\Contract;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\Contract\ContractLots;
use Exception;

class ContractLotsService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
     */
    protected $request;
    /** * User Service Class Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function createContract(){
        $contract = new ContractLots();
        return $this->builder($contract);
    }

    public function updateContract($id):ContractLots{
        $contract = ContractLots::findOrFail($id);
        return $this->builder($contract);
    }

    private function builder(ContractLots $contract):ContractLots {
        $date = $this->request->all();
        foreach ($date as $key => $value){
            try {
                $contract->{$key} = $value;
            } catch (Exception $e) {
                return false;
            }
        }
       $contract->save();
       return $contract;

    }

}
