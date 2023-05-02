<?php


namespace App\Http\Controllers\Api\Participant;

use App\Repositories\Participant\ParticipantRowRepository;
use App\Http\Requests\Participant\CreateParticipantRowRequest;
use App\Http\Requests\Participant\UpdateParticipantRowRequest;
use App\Services\Participant\ParticipantRowService;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Transformers\Participant\ParticipantRowTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ParticipantRowController extends AbstractController
{

    /**
     * ParticipantRow.
     *
     * @var     ParticipantRowRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $participantRow;

    /**
     * User controller constructor.
     *
     * @param ParticipantRowRepository $participantRow
     */
    public function __construct( ParticipantRowRepository $participantRow){
        parent::__construct();
        $this->participantRow = $participantRow;
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $participantRow = $this->participantRow->getByGroupId($id);
        return $this->respondWithPagination($participantRow, new ParticipantRowTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     * @param  int  $organize_row_id
     * @return \Illuminate\Http\JsonResponse
    */
    public function getByOrganizeRowId(int $organize_row_id)
    {
        $participantRow = $this->participantRow->getByOrganizeRowId($organize_row_id);
        // return $participantRow;
        return $this->respondWithPagination($participantRow, new ParticipantRowTransformer($this->shield->id()));
    }

    public function getWinnerByOrganizeRowId(int $organize_row_id)
    {
        $participantRow = $this->participantRow->getWinnerByOrganizeRowId($organize_row_id);
        return $participantRow;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(CreateParticipantRowRequest $request){
        $service = new ParticipantRowService($request);
        $service->createParticipantRow();
        return $this->respondWithStatus(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParticipantRowRequest $request,int $id){
        $service = new ParticipantRowService($request);
        $service->updateParticipantRow($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id){
        $this->participantRow->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }
    /**
     * Display a listing of the resource.
     * @param  int  $organize_row
     * @return JsonResponse
     */
    public function getByOrganize(int $organize_row)
    {
        $participant = $this->participantRow->getByGroupId($organize_row);
        return $this->respondWithPagination($participant, new ParticipantTransformer($this->shield->id()));
    }

    /**
     * Check is set equal price by organize row id.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function checkEqualPrice(Request $request, int $row_id){
        $service = new ParticipantRowService($request);
        $result = $service->checkEqualPrice($row_id);
        return $this->respondWithStatus($result);
    }
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function getHistories($id)
    {
        $participantRow = $this->participantRow->retrieveById($id)->histories()->orderBy("performed_at","desc")->select("meta","performed_at")->paginate(10);

        $response = [
            'data' => $participantRow->items(),
            'pagination' => [
                'total' => $participantRow->total(),
                'count' => count($participantRow->items()),
                'page' => $participantRow->currentPage(),
                'continue' => $participantRow->hasMorePages()
            ]
        ];
        $response['timestamp'] = Request::server('REQUEST_TIME_FLOAT');
        return Response::json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        return $this->respondWithPagination($participantRow, new ParticipantRowTransformer($this->shield->id()));
    }

}
