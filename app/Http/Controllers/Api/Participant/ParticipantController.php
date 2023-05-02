<?php


namespace App\Http\Controllers\Api\Participant;

use App\Repositories\Participant\ParticipantRepository;
use App\Http\Requests\Participant\CreateParticipantRequest;
use App\Http\Requests\Participant\UpdateParticipantRequest;
use App\Services\Participant\ParticipantService;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Transformers\Participant\ParticipantTransformer;
use Illuminate\Http\JsonResponse;

class ParticipantController extends AbstractController
{
    /**
     * Participant.
     * @var     ParticipantRepository
     * @access  protected
     * @since   1.0.0 SelectedParticipantController
    */
    protected $participant;
    /**
     * User controller constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct( ParticipantRepository $participant){
        parent::__construct();
        $this->participant = $participant;
    }

    public function index(){
        $participant = $this->participant->paginate();
        return $this->respondWithPagination($participant, new ParticipantTransformer($this->shield->id()));
    }


    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return JsonResponse
    */
    public function show(int $id)
    {
        $participant = $this->participant->getByOrganizeId($id);
        return $this->respondWithPagination($participant, new ParticipantTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     * @param int $group_id
     * @return JsonResponse
    */
    public function getByGroupId(int $group_id)
    {
        $participant = $this->participant->getByGroupId($group_id);
        return $this->respondWithPagination($participant, new ParticipantTransformer($this->shield->id()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateParticipantRequest $request
     * @return JsonResponse
    */
    public function store(CreateParticipantRequest $request){
        $service = new ParticipantService($request);
        $service->createParticipant();
        // return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateParticipantRequest $request
     * @param int $id
     * @return JsonResponse
    */
    public function update(UpdateParticipantRequest $request,int $id){
        $service = new ParticipantService($request);
        $service->updateParticipant($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function destroy(int $id){
        $this->participant->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }

}
