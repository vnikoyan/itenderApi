<?php


namespace App\Services\PurchasingProcess;


use App\Models\PurchasingProcess\PurchasingProcessPercent;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class PurchasingProcessPercentService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
     */
    protected  $request;

    /**
     *
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @param $id
     * @return PurchasingProcessPercent
     */
    public function updatePurchasingProcessPercent($id):PurchasingProcessPercent{
        //TODO validats uva uxarkel user_id
        $purchasingProcess = PurchasingProcessPercent::findOrFail($id);
        return $this->builder($purchasingProcess);
    }
    /**
     * @return PurchasingProcessPercent
     */
    public function createPurchasingProcessPercent():PurchasingProcessPercent{
        //TODO validats uva uxarkel user_id
        $purchasingProcess = new PurchasingProcessPercent();
        return $this->builder($purchasingProcess);
    }
    /**
     * @param PurchasingProcess $purchasingProcess
     * @return PurchasingProcessPercent
     */
    private function builder(PurchasingProcessPercent $purchasingProcess):PurchasingProcessPercent {
        $intekater = false;
        for($i = 12;$i > 0;$i--){
            $ke = "month_".$i;
            if(empty($this->request->{$ke}) && !$intekater ){
                $this->request->merge([$ke => 100]);
            }else{
                $intekater = true;
            }
        }
        for ($i=1;$i < 12;$i++){
            $ke = "month_".$i;
            $keMin = "month_".($i-1);
            if( empty($this->request->{$ke})  ) {
                $val = (!$this->request->{$keMin}) ? 0 :  $this->request->{$keMin};
                $this->request->merge([$ke => $val]);
            }
        }
        foreach ($this->request->all() as $key => $value){
            try {
                $purchasingProcess->{$key} = $value;
            } catch (Exception $e) {
                return false;
            }
        }
        $purchasingProcess->save();
        return $purchasingProcess;
    }
}
