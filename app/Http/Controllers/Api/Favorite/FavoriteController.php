<?php


namespace App\Http\Controllers\Api\Favorite;

use Validator;
use Auth;
use App\Repositories\Tender\TenderRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Resources\Tender\FavoriteTenderResource;
use App\Models\Tender\TenderState;
use App\Models\Tender\FavoriteTenderState;
use App\Models\PurchasingProcess\PurchasingProcessParent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Support\VueTable\EloquentVueTables;


class FavoriteController extends AbstractController
{


    public function favoriteTender(Request $request){
        $user = auth('api')->user();
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'tender_state_id' => ['required', 'integer'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $favoriteTenderState = FavoriteTenderState::where('user_id',$user->id)
                                                  ->where('tender_state_id',$data['tender_state_id'])
                                                  ->first();

        if( empty($favoriteTenderState) ){

            $favoriteTenderState = new FavoriteTenderState;
            $favoriteTenderState->user_id = $user->id;
            $favoriteTenderState->tender_state_id = $data['tender_state_id'];
            $favoriteTenderState->save();
            
            return  response()->json(['error' => false, 'message' => 'added to favorites' ]);
        }else{

            FavoriteTenderState::where('user_id',$user->id)
                               ->where('tender_state_id',$data['tender_state_id'])
                               ->delete();

            return  response()->json(['error' => false, 'message' => 'removed from  favorites' ]);
        }
    }

    public function getfavoriteTender(Request $request){
        ini_set('memory_limit', '-1');

        $user = auth('api')->user();
        $vuetable = new EloquentVueTables();
        $tenders = FavoriteTenderState::select('favorite_tender_states.*', 'tender_state.end_date')->with("tender")->leftJoin("tender_state","tender_state.id","=","favorite_tender_states.tender_state_id")->where('favorite_tender_states.user_id',$user->id)->orderBy('tender_state.end_date','ASC');

        if(!empty($request['status'])){
            switch ($request['status']) {
                case 'active':
                    $tenders->whereHas('tender', function ($attachmentQuery) {
                        $attachmentQuery->where('end_date','>',date("Y-m-d H:i:s"));
                    });
                    break;
                 case 'finished':
                    $tenders->whereHas('tender', function ($attachmentQuery) {
                        $attachmentQuery->where('end_date','<',date("Y-m-d H:i:s"));
                    });
                    break;
                default:
                    break;
            }
        }

        $tenders = $vuetable->get($tenders, ['favorite_tender_states.*'], ['title', 'password', 'customer_name'], ["tender" => ["password","title"]]);

        return $this->respondWithPaginationServerTable(FavoriteTenderResource::collection($tenders['data']), $tenders['count']);

    }


}
