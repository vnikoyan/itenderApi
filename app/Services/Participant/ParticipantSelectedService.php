<?php


namespace App\Services\Participant;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\Participant\SelectedParticipants;

class ParticipantSelectedService
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
        $participant = SelectedParticipants::findOrFail($id);

        if($this->request->organize_row_id){
            $participant->organize_row_id   = $this->request->organize_row_id;
        }
        if($this->request->participant_group_id){
            $participant->participant_group_id   = $this->request->participant_group_id;
        }
        if($this->request->bank){
            $participant->bank   = $this->request->bank;
        }
        if($this->request->hh){
            $participant->hh   = $this->request->hh;
        }
        if($this->request->director_full_name){
            $participant->director_full_name   = $this->request->director_full_name;
        }
        if($this->request->name){
            $participant->name   = $this->request->name;
        }
        if($this->request->manufacturer_name){
            $participant->manufacturer_name   = $this->request->manufacturer_name;
        }
        if($this->request->country_of_origin){
            $participant->country_of_origin   = $this->request->country_of_origin;
        }
        $participant->save();
    }

    public function builder():void{
        $selected =  new SelectedParticipants();
        $selected->organize_row_id         = $this->request->organize_row_id;
        $selected->participant_group_id    = $this->request->participant_group_id;
        $selected->bank                    = $this->request->bank;
        $selected->hh                      = $this->request->hh;
        $selected->director_full_name      = $this->request->director_full_name;
        $selected->name                    = $this->request->name;
        $selected->manufacturer_name       = $this->request->manufacturer_name;
        $selected->country_of_origin       = $this->request->country_of_origin;
        $selected->save();
    }

}
