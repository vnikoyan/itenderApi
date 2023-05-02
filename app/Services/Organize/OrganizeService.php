<?php
namespace App\Services\Organize;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Organize\Organize;

class OrganizeService
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

    public function createOrganize(){
		return $this->bilder();
	}

    /**
     * @param $id
     * @return Organize
     */
    public function updateOrganize($id):Organize{
	    //TODO validats uva uxarkel user_id
		return $this->edit($id);
	}


    /**
     * @return mixed
    */
	private function bilder():int {
        $request =  $this->request->all();
        $request["created_at"] = date("Y-m-d h:i:s");
        if(!empty($request['name'])){
            $request["name"]      = json_encode($request['name']);
            $request["code"]      = json_encode($request['code']);
            $request["rights_responsibilities_fulfillment"] = $request['rights_responsibilities_fulfillment'];
        }
        $request["user_id"]   = auth('api')->user()->id;
        if(!empty($request['evaluator_member'])){
            $request["evaluator_member"]         = json_encode($request['evaluator_member']);
            $request["evaluator_president"]      = json_encode($request['evaluator_president']);
            $request["evaluator_secretary_name"] = json_encode($request['evaluator_secretary_name']);
        }else{
            $organize = Organize::select("organize.*","organize.evaluator_secretary_name as esn","organize.evaluator_president as ep","organize.evaluator_secretary_position as esp")->where("user_id",$request["user_id"])->orderBy("id","DESC")->first();
            if( !is_null($organize) ){
                $request["evaluator_member"]             = $organize->evaluator_member;
                $request["evaluator_president"]          = $organize->ep;
                $request["evaluator_secretary_name"]     = $organize->esn;
                $request["evaluator_secretary_email"]    = $organize->evaluator_secretary_email;
                $request["evaluator_secretary_phone"]    = $organize->evaluator_secretary_phone;
                $request["evaluator_secretary_position"] = $organize->esp;
            }
        }
        return Organize::insertGetId($request);
	}
    /**
     * @param int $id
     * @return mixed
     */
	private function edit(int $id):Organize{
		$organize = Organize::findOrFail($id);
        foreach ($this->request->all() as $key => $value){
               try {
                   if($key == "evaluator_member"){
                       $value = json_encode($value);
                   }
                   $organize->{$key} = $value;

               } catch (Exception $e) {
                  return false;
               }
            }
			$organize->save();
			return $organize;
	}


}
