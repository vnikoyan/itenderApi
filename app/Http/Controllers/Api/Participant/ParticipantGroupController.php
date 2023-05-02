<?php

namespace App\Http\Controllers\Api\Participant;

use App\Models\Participant\ParticipantGroup;
use App\Services\Participant\ParticipantGroupService;
use App\Http\Requests\Participant\CreateParticipantGroupRequest;
use App\Http\Requests\Participant\UpdatePersonalInfoParticipantGroupRequest;
use App\Http\Controllers\Api\AbstractController;
use App\Repositories\Participant\ParticipantRepository;
use App\Support\Transformers\Participant\ParticipantGroupTransformer;
use Illuminate\Http\Request;
use ZipArchive;
use Validator;

class ParticipantGroupController extends AbstractController
{
    protected $participant_group;
    /**
     * User controller constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct( ParticipantRepository $participant_group){
        parent::__construct();
        $this->participant_group = $participant_group;
    }

    public function index(){
        $participant_group = $this->participant_group->paginate();
        return $this->respondWithPagination($participant_group, new ParticipantGroupTransformer($this->shield->id()));
    }

    public function show(int $id)
    {
        $participant_group = $this->participant_group->getByOrganizeId($id);
        return $participant_group;
        // return $this->respondWithPagination($participant_group, new ParticipantTransformer($this->shield->id()));
    }

    public function getWonLots(int $id)
    {
        $participant_group = $this->participant_group->getWonLotsByOrganizeId($id);
        return $participant_group;
    }

    public function processXML(Request $request){
        $service = new ParticipantGroupService($request);
        $response = $service->processXMLandZIP();
        if(!$response){
            return $this->respondWithStatus(false);
        }
        return $this->respondWithStatus(true, $response);
    }
    
    public function store(CreateParticipantGroupRequest $request){
        $service = new ParticipantGroupService($request);
        return $service->createParticipantGroup();
    }

        public function createInvoiceParticipantGroup(Request $request){
        $service = new ParticipantGroupService($request);
        return $service->createInvoiceParticipantGroup();
    }

    public function update(CreateParticipantGroupRequest $request, int $id){
        $service = new ParticipantGroupService($request);
        $service->updateParticipantGroup($id);
        return $this->respondWithStatus(true);
    }

    public function destroy(int $id){
        $this->participant_group->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }

    public function addPersonalInfo(UpdatePersonalInfoParticipantGroupRequest $request, int $id){
        $service = new ParticipantGroupService($request);
        $service->addPersonalInfo($id);
        return $this->respondWithStatus(true);
    }

    public  function saveContractDocument(Request $request){
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'id' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        ParticipantGroup::where('id', $data['id'])
            ->update(['signed_contract_hy' => $data["signed_contract_hy"]]);
    }
}
