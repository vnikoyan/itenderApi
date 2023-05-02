<?php


namespace App\Http\Controllers\Api\Participant;

use App\Repositories\Participant\SelectedParticipantRepository;
use App\Http\Requests\Participant\CreateSelectedParticipantRequest;
use App\Http\Requests\Participant\UpdateSelectedParticipantRequest;
use App\Services\Participant\ParticipantSelectedService;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Transformers\Participant\SelectedParticipantTransformer;
use Illuminate\Http\JsonResponse;

class SelectedParticipantController extends AbstractController
{
    /**
     * Participant.
     * @var     SelectedParticipantRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $selectedParticipant;
    /**
     * User controller constructor.
     *
     * @param SelectedParticipantRepository $selectedParticipant
     */
    public function __construct( SelectedParticipantRepository $selectedParticipant){
        parent::__construct();
        $this->selectedParticipant = $selectedParticipant;
    }
    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $selectedParticipant = $this->selectedParticipant->getByOrganizeId($id);
        return $this->respondWithPagination($selectedParticipant, new SelectedParticipantTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     * @param int $group_id
     * @return JsonResponse
     */
    public function getByGroupId(int $group_id)
    {
        $selectedParticipant = $this->selectedParticipant->getByGroupId($group_id);
        return $this->respondWithPagination($selectedParticipant, new SelectedParticipantTransformer($this->shield->id()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSelectedParticipantRequest $request
     * @return JsonResponse
     */
    public function store(CreateSelectedParticipantRequest $request){
        $service = new ParticipantSelectedService($request);
        $service->createParticipant();
        return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateSelectedParticipantRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateSelectedParticipantRequest $request,int $id){
        $service = new ParticipantSelectedService($request);
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
        $this->selectedParticipant->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }
}
