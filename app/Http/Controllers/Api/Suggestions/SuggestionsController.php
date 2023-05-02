<?php

namespace App\Http\Controllers\Api\Suggestions;

use App\Http\Controllers\Api\AbstractController;
use App\Services\Suggestions\SuggestionsService;
use App\Support\Transformers\Suggestions\SuggestionsTransformer;
use App\Repositories\Suggestions\SuggestionsRepository;
use App\Http\Resources\Suggestions\SuggestionsResource;
use App\Models\Participant\ParticipantGroup;
use Validator;
use App\Models\Suggestions\Suggestions;
use App\Models\Suggestions\FavoriteSuggestions;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

class SuggestionsController extends AbstractController
{
    protected $suggestion;
    /**
     * Contract controller constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct(SuggestionsRepository $suggestion){
        parent::__construct();
        $this->suggestion = $suggestion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suggestion = $this->suggestion->paginate();
        return $this->respondWithPagination($suggestion, new SuggestionsTransformer($this->shield->id()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $service = new SuggestionsService($request);
        $service->createSuggestion();
        return $this->respondWithStatus(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = new SuggestionsService($request);
        $service->createSuggestions();
        return $this->respondWithStatus(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $suggestion = $this->suggestion->getByProviderId($id);
        return $suggestion;
    }

    public function getAll(Request $request){
        $suggestion = $this->suggestion->getAll($request);
        return $this->respondWithPaginationServerTable(SuggestionsResource::collection($suggestion['data']), $suggestion['count']);
    }

    public function getByOrganizeId($id){
        $suggestion = Suggestions::where([
            ['organize_id', $id],
            ['provider_id', auth('api')->user()->id]
        ])->first();
        $participant = ParticipantGroup::where([
            ['organize_id', $id],
            ['user_id', auth('api')->user()->id]
        ])->first();
        if($suggestion){
            $suggestion->participant = $participant ? $participant : false;
        }
        return $suggestion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $service = new SuggestionsService($request);
        $service->updateSuggestion($id);
        return $this->respondWithStatus(true);
    }

    /**
     * Upload the Additional File.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function uploadAdditionalFile(Request $request, int $id)
    {
        $service = new SuggestionsService($request);
        $suggestion = $service->uploadAdditionalFile($id);
        return $suggestion;
    }
    /**
     * Upload the Additional File.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function deleteAdditionalFile(Request $request, int $id)
    {
        $service = new SuggestionsService($request);
        $suggestion = $service->deleteAdditionalFile($id);
        return $suggestion;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->suggestion->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }
    /**
     * Cacnel suggestion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel(int $id)
    {
        $this->suggestion->cancel($id);
        return $this->respondWithStatus(true);
    }

    public function favoriteSuggestion(Int $suggestion_id){
        $user = auth('api')->user();
        $favoriteTenderState = FavoriteSuggestions::where('user_id',$user->id)
                                                  ->where('suggestion_id', $suggestion_id)
                                                  ->first();

        if(empty($favoriteTenderState)){
            $favoriteTenderState = new FavoriteSuggestions;
            $favoriteTenderState->user_id = $user->id;
            $favoriteTenderState->suggestion_id =  $suggestion_id;
            $favoriteTenderState->save();
            
            return  response()->json(['error' => false, 'message' => 'added to favorites' ]);
        }else{

            FavoriteSuggestions::where('user_id',$user->id)
                               ->where('suggestion_id', $suggestion_id)
                               ->delete();

            return  response()->json(['error' => false, 'message' => 'removed from favorites' ]);
        }
    }

}
