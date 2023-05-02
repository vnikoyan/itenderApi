<?php


namespace App\Services\PurchasingProcess;


use App\Models\PurchasingProcess\PurchasingProcess;
use App\Models\PurchasingProcess\PurchasingProcessUser;
use Illuminate\Http\Request;

class PurchasingProcessService
{
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

    public function createPurchasingProcess():void{
        $this->builder();
    }
    public function updatePurchasingProcess(int $id):void{
        $participant = PurchasingProcess::findOrFail($id);

        if($this->request->procurement_plan_id){
            $participant->procurement_plan_id   = $this->request->procurement_plan_id;
        }
        if($this->request->count){
            $participant->count   = $this->request->count;
        }
        if($this->request->code){
            $participant->code   = $this->request->code;
        }
        if($this->request->address){
            $participant->address   = $this->request->address;
        }
        if($this->request->other_requirements){
            $participant->other_requirements   = $this->request->other_requirements;
        }
        if($this->request->is_full_decide){
            $participant->is_full_decide   = $this->request->is_full_decide;
        }
        if($this->request->is_all_participants){
            $participant->is_all_participants   = $this->request->is_all_participants;
        }
        if($this->request->deadline){
            $participant->deadline   = $this->request->deadline;
        }
        $participant->save();
    }

    public function builder():void{
            $participant = new PurchasingProcess();
            $participant->procurement_plan_id   =  $this->request->procurement_plan_id;
            $participant->count                 =  $this->request->count;
            $participant->organisation_id       =  auth('api')->user()->parent_id;
            $participant->code                  =  $this->request->code;
            $participant->address               =  $this->request->address;
            $participant->other_requirements    =  $this->request->other_requirements;
            $participant->is_full_decide        =  $this->request->is_full_decide;
            $participant->is_all_participants   =  $this->request->is_all_participants;
            $participant->deadline              =  $this->request->deadline;
            $participant->save();
            $this->crateUser($participant->id);
    }

    public  function crateUser(int $participantID):void{
        if(!empty($this->request->users)){
            foreach ($this->request->users as $key => $value){
                $participantUser = new PurchasingProcessUser();
                $participantUser->purchasing_process_id = $participantID;
                $participantUser->user_id = $value["user_id"];
                $participantUser->save();
            }
        }
    }

}
