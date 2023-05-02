<?php
namespace App\Services\Organize;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Organize\OrganizeRowPercent;

class OrganizeRowPercentService
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
     * @return OrganizeRowPercent
     */
    public function updateOrganizeRowPercent($id):OrganizeRowPercent{
        //TODO validats uva uxarkel user_id
        $organize = OrganizeRowPercent::findOrFail($id);
        return $this->builder($organize);
    }
    /**
     * @return OrganizeRowPercent
    */
    public function createOrganizeRowPercent():OrganizeRowPercent{
        //TODO validats uva uxarkel user_id
        $organize = new OrganizeRowPercent();
        return $this->builder($organize);
    }
    /**
     * @param OrganizeRow $organize
     * @return OrganizeRowPercent
     */
    private function builder(OrganizeRowPercent $organize):OrganizeRowPercent {

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
                $organize->{$key} = $value;
            } catch (Exception $e) {
                return false;
            }
        }
        $organize->save();
        return $organize;
    }

}
