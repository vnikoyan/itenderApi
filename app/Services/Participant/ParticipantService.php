<?php


namespace App\Services\Participant;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\Participant\Participant;
use App\Models\Participant\ParticipantRow;

class ParticipantService
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

    public function createParticipant():void{
        $this->builder();
    }

    public function updateParticipant(int $id):void{
        $participant = Participant::findOrFail($id);

        if($this->request->tin){
            $participant->tin   = $this->request->tin;
        }
        if($this->request->name){
            $participant->name   = $this->request->name;
        }
        if($this->request->address){
            $participant->address   = $this->request->address;
        }
        if($this->request->email){
            $participant->email   = $this->request->email;
        }
        if($this->request->phone){
            $participant->phone   = $this->request->phone;
        }
        if($this->request->date_of_submission){
            $participant->date_of_submission   = $this->request->date_of_submission;
        }
        $participant->save();
    }

    public function builder():void{
        if(empty($this->request->group_id)){
            $group_id = (string) time();
        }else{
            $group_id = $this->request->group_id.time();
        }
        if(isset($this->request->lots)){
            $insertArrayLots = [];
            foreach($this->request->lots as $key => $lot){
                $insertArrayLots[$key] = [
                    "row_group_id" => $group_id,
                    "organize_row_id" => $lot['organize_row_id'],
                    "cost" => $lot['overall'], 
                    // "profit" => $lot['overall'],
                    "value" => $lot['total_price'], 
                    "vat" => $lot['vat'], 
                ];
            }
            ParticipantRow::insert($insertArrayLots);
        }
        $insertArray = [];
        foreach($this->request->participant as $key => $request){
            $insertArray[$key] = [
                "group_id" => $group_id,
                "organize_id" => $this->request->organize_id,
                "email" => $request['email'],
                "phone" => $request['phone'],
                "tin" => $request['tin'],
                "name" => json_encode($request["name"]),
                "address" => json_encode($request["address"]),
            ];
        }
        Participant::insert($insertArray);
    }

}
