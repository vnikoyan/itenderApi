<?php


namespace App\Http\Controllers\Api\Tender;

use App\Events\NotificationEvent;
use Auth;
use App\Repositories\Tender\TenderRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Resources\Tender\TenderResource;
use App\Http\Resources\Tender\TenderLandingResource;
use App\Http\Resources\Tender\TenderRowsResource;
use App\Models\Categories\Categories;
use App\Models\Cpv\Cpv;
use App\Models\User\User;
use App\Models\Tender\TenderState;
use App\Models\Tender\TenderStateArchive;
use App\Models\Settings\UserFilters;
use App\Models\Settings\Region;
use App\Models\Tender\TendersTableConfig;
use App\Models\Tender\FavoriteTenderState;
use App\Models\Tender\TenderStateCpv;
use App\Models\PurchasingProcess\PurchasingProcessParent;
use App\Models\Tender\Organizator;
use App\Models\Tender\Procedure;
use App\Models\UserCategories\UserCategories;
use App\Models\UserCategories\UserCpvs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Support\VueTable\EloquentVueTables;
use App\Jobs\ProcessNewTenderAdded;
use App\Jobs\ProcessNewTenderAddedToList;
use App\Imports\CpvList;
use App\Models\Tender\UserCorrespondingTenders;
use Illuminate\Support\Facades\Log;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TenderCreated;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class TenderController extends AbstractController
{
    /**
     * Tender.
     *
     * @var     TenderRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $tender;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function get($model, Array $fields, Array $relations) {
        extract(Input::only('query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn'));

        $data = $model->select($fields);

        if (isset($query) && $query) {
            $data = $byColumn==1?$this->filterByColumn($data, $query):
                $this->filter($data, $query, $fields);
        }

        $count = $data->count();

        $data->limit($limit)
            ->skip($limit * ($page-1));

        if (isset($orderBy) && $orderBy):
            $direction = $ascending==1?"ASC":"DESC";
            $data->orderBy($orderBy,$direction);
        endif;

        if (count($relations) > 0):
            $data->with($relations);
        endif;

        $results = $data->get()->toArray();

        return ['data'=>$results,
            'count'=>$count];
    }

    function differenceInHours($startdate,$enddate){
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = abs($endtimestamp - $starttimestamp)/3600;
        return $difference;
    }
    
    public function getLandingTenders(){
        $vuetable = new EloquentVueTables();
        $is_closed = TenderState::where("is_closed",1)
            ->where('end_date','>', date("Y-m-d H:i:s"))
            ->where('is_competition', 1)
            ->inRandomOrder()
            ->limit(10)
            ->with('getCpv')
            ->with('getCategory')
            ->get();
        if(count($is_closed) >= 10){
            return $this->respondWithPaginationServerTable(TenderLandingResource::collection($is_closed), count($is_closed));
        } else {
            $count = 10 - (int) count($is_closed);
            $tenders = TenderState::where('kind', 'private')
                ->where('end_date','>',date("Y-m-d H:i:s"))
                ->where('is_competition', 1)
                ->where("is_closed",0)
                ->inRandomOrder()
                ->limit($count)
                ->with('getCpv')
                ->with('getCategory')
                ->get();
            $all_tenders = $is_closed->merge($tenders);
            if(count($all_tenders) >= 10 ){
                return $this->respondWithPaginationServerTable(TenderLandingResource::collection($all_tenders), count($all_tenders));
            }else{
                $count = 10 - (int) count($all_tenders);
                $tenders = TenderState::where('kind', 'one_person')
                    ->where('end_date','>',date("Y-m-d H:i:s"))
                    ->where('is_competition', 1)
                    ->where("is_closed",0)
                    ->inRandomOrder()
                    ->limit($count)
                    ->with('getCpv')
                    ->with('getCategory')
                    ->get();
    
                $full_tenders = $all_tenders->merge($tenders);
            }
            if(count($full_tenders) >= 10 ){
                return $this->respondWithPaginationServerTable(TenderLandingResource::collection($full_tenders), count($all_tenders));
            }else{
                $count = 10 - (int) count($all_tenders);
                $tenders = TenderState::inRandomOrder()->limit($count)->where('end_date','>',date("Y-m-d H:i:s"))->where('is_competition', 1)->where("is_closed",0)->with('getCpv')->with('getCategory')->get();
    
                $full_tenders = $all_tenders->merge($tenders);
                return $this->respondWithPaginationServerTable(TenderLandingResource::collection($full_tenders), count($full_tenders));
            }
        }
    }

    public function index(Request $request){
        ini_set('memory_limit', '-1');
        $vuetable = new EloquentVueTables();
        $index = $request['index'];
        $withCustomFilters = $request['withCustomFilters'];
        $user_id = auth('api')->user()->id;

        $select_values = ['id','title','start_date','end_date',
                        'regions','type','tender_type','is_million10','is_competition','is_new','is_closed','is_with_model',
                        'estimated','estimated_file','customer_name','password','invitation_link','cpv',
                        'category','organizer_id','kind','procedure_type','guaranteed','contract_html',
                        'one_person_organize_id','tender_state_id','type_name','created_at','beneficiari','manager_id','estimated_price'];
        if(!empty($request['tenderId'])){
            $old_tenders = TenderStateArchive::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements")->where('id', $request['tenderId']);
            $live_tenders = TenderState::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements")->unionAll($old_tenders)->where('id', $request['tenderId']);
            $tenders = $live_tenders;
            $tenders = $tenders->get();
            return $this->respondWithPaginationServerTable(TenderResource::collection($tenders), count($tenders));
        } else if(!empty($request['tenderGroupId'])){
            $live_tenders = TenderState::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements")->where('id', $request['tenderGroupId'])->orWhere('tender_state_id', $request['tenderGroupId']);
            $old_tenders = TenderStateArchive::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements")->where('id', $request['tenderGroupId'])->orWhere('tender_state_id', $request['tenderGroupId']);
            $tenders = $live_tenders->unionAll($old_tenders);
            $tenders = $tenders->get();
            return $this->respondWithPaginationServerTable(TenderResource::collection($tenders), count($tenders));
        } else {
            if($request['query']){
                $live_tenders = TenderState::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements");
                $live_tenders = $vuetable->handleFilters($live_tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"], "getCpv" => ["cpv_name"]]);

                $old_tenders = TenderStateArchive::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements");
                $old_tenders = $vuetable->handleFilters($old_tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"], "getCpv" => ["cpv_name"]]);
                $tenders = $old_tenders->unionAll($live_tenders);

                if(empty($request['order'])){
                    $tenders->orderBy('created_at', 'DESC');
                } else {
                    if($request['order'] === 'byStartDate'){
                        $tenders->orderBy('start_date', 'DESC');
                    } elseif($request['order'] === 'byEndDate') {
                        $tenders->orderBy('end_date', 'ASC');
                    }
                }

                $tenders = $vuetable->get($tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"], "getCpv" => ["cpv_name"]]);
                return $this->respondWithPaginationServerTable(TenderResource::collection($tenders['data']), $tenders['count']);
            }
            if(!empty($request['favorite']) && $request['favorite'] === 'true'){
                $old_tenders = TenderStateArchive::select(...$select_values)
                ->where('is_competition', 1)->withCount("tenderAnnouncements")->withCount("announcements")
                ->whereHas('favorite', function($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });

                $live_tenders = TenderState::select(...$select_values)
                ->where('is_competition', 1)->withCount("tenderAnnouncements")->withCount("announcements")
                ->whereHas('favorite', function($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });

                $tenders = $live_tenders->unionAll($old_tenders);

                if(empty($request['order'])){
                    $tenders->orderBy('created_at', 'DESC');
                } else {
                    if($request['order'] === 'byStartDate'){
                        $tenders->orderBy('start_date', 'DESC');
                    } elseif($request['order'] === 'byEndDate') {
                        $tenders->orderBy('end_date', 'ASC');
                    }
                }
                
                $tenders = $vuetable->get($tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"]]);
                return $this->respondWithPaginationServerTable(TenderResource::collection($tenders['data']), $tenders['count']);
            }
            if($withCustomFilters){
                $tenders = $this->handleFilters($request, $select_values);
                $tenders = $vuetable->get($tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"]]);
                return $this->respondWithPaginationServerTable(TenderResource::collection($tenders['data']), $tenders['count']);
            } else {
                // $user_tenders = array_keys(UserCorrespondingTenders::where('user_id', $user_id)->get()->keyBy('tender_id')->toArray());
                // if(false){
                //     $tenders = TenderState::select(...$select_values)
                //         ->withCount("tenderAnnouncements")->withCount("announcements")
                //         ->whereIn('tender_state.id', $user_tenders);
                //     if(empty($request['orderBy'])){
                //         $tenders->orderBy('tender_state.start_date', 'DESC');
                //     }
                //     $tenders = $vuetable->get($tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"]]);
                //     return $this->respondWithPaginationServerTable(TenderResource::collection($tenders['data']), $tenders['count']);
                // } else {
                    $tenders = $this->index_old($request);
                    $tenders = $vuetable->get($tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"]]);
                    return $this->respondWithPaginationServerTable(TenderResource::collection($tenders['data']), $tenders['count']);
                // }
            }
        }
    }
    

    public function handleFilters(Request $request, $select_values){
        ini_set('memory_limit', '-1');
        $index = $request['index'];
        $filters = $request->all();
        $old_tenders = false;
        if(!empty($filters['status'])){
            switch ($filters['status']) {
                case 'active':
                    $tenders = TenderState::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements");
                    break;
                case 'finished':
                    $tenders = TenderStateArchive::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements");
                    break;
                case 'all':
                    $tenders = TenderState::select(...$select_values);
                    $old_tenders = TenderStateArchive::select(...$select_values);
                    break;
                default:
                    $tenders = TenderState::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements");
                    break;
            }
        } else {
            $tenders = TenderState::select(...$select_values)->withCount("tenderAnnouncements")->withCount("announcements");
        }
        if(empty($request['order'])){
            $tenders->orderBy('created_at', 'DESC');
            if($old_tenders){
                $old_tenders->orderBy('created_at', 'DESC');
            }
        } else {
            if($request['order'] === 'byStartDate'){
                $tenders->orderBy('start_date', 'DESC');
                if($old_tenders){
                    $old_tenders->orderBy('start_date', 'DESC');
                }
            } elseif($request['order'] === 'byEndDate') {
                $tenders->orderBy('end_date', 'ASC');
                if($old_tenders){
                    $old_tenders->orderBy('end_date', 'ASC');
                }
            }
        }
        $user_id = auth('api')->user()->id;
        if(!empty($filters['competition'])){
            $tenders->where('is_competition', $filters['competition'] === 'true' ? 1 : null);
            if($old_tenders){
                $old_tenders->where('is_competition', $filters['competition'] === 'true' ? 1 : null);
            }
        }
        if(!empty($filters['category'])){
            if($filters['category'] === 'onlyMy'){
                // $user_cpvs_array = auth('api')->user()->selectedCpvs();
                // $tenders->whereHas('getCpv', function($query) use ($user_cpvs_array) {
                //     $query->whereIn('cpv_id', $user_cpvs_array);
                // });
                $user_cpvs = array_keys(UserCpvs::where('user_id', $user_id)->get()->keyBy('cpv_id')->toArray());
                $tenders->whereHas('getCpv', function($query) use ($user_cpvs) {
                    $query->whereIn('cpv_id', $user_cpvs);
                });
                if($old_tenders){
                    $old_tenders->whereHas('getCpv', function($query) use ($user_cpvs) {
                        $query->whereIn('cpv_id', $user_cpvs);
                    });
                }
            }
        }
        if(isset($filters['region']) && is_array($filters['region'])){
            $tenders->whereIn('regions', $filters['region']);
            if($old_tenders){
                $old_tenders->whereIn('regions', $filters['region']);;
            }
        }
        if(isset($filters['organizator']) && is_array($filters['organizator'])){
            $tenders->whereIn('organizer_id', $filters['organizator']);
            if($old_tenders){
                $old_tenders->whereIn('organizer_id', $filters['organizator']);
            }
        }
        if($filters['guaranteed'] === '0' || $filters['guaranteed'] === '1'){
            $tenders->where('guaranteed', $filters['guaranteed']);
            if($old_tenders){
                $old_tenders->where('guaranteed', $filters['guaranteed']);
            }
        }
        if(!empty($filters['type'])){
            $tenders->where('kind', $filters['type']);
            if($old_tenders){
                $old_tenders->where('kind', $filters['type']);
            }
            if($filters['type'] === 'competitive' || $filters['type'] === 'one_person'){
                $cpv_groups = [];
                if(isset($filters['products']) && is_array($filters['products'])){
                    $cpv_groups = array_merge($cpv_groups, $filters['products']);
                }
                if(isset($filters['services']) && is_array($filters['services'])){
                    $cpv_groups = array_merge($cpv_groups, $filters['services']);
                }
                if(isset($filters['works']) && is_array($filters['works'])){
                    $cpv_groups = array_merge($cpv_groups, $filters['works']);
                }
                $cpvs = [];
                foreach ($cpv_groups as $cpv_group) {
                    $cpv = Cpv::find($cpv_group);
                    $cpv_childrens = $cpv->children;
                    array_push($cpvs, $cpv);
                    $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                    foreach ($cpv_childrens as $cpv_group) {
                        $cpv_childrens = $cpv_group->children;
                        $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                        foreach ($cpv_childrens as $cpv_group) {
                            $cpv_childrens = $cpv_group->children;
                            $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                            foreach ($cpv_childrens as $cpv_group) {
                                $cpv_childrens = $cpv_group->children;
                                $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                            }
                        }
                    }
                }
                $tenders->where(function ($query) use($cpvs) {
                    for ($i = 0; $i < count($cpvs); $i++){
                        $query->orwhere('cpv', 'like',  '%"' . $cpvs[$i]['id'] .'"%');
                    }      
                });
            }
            if($filters['type'] === 'competitive'){
                if(!empty($filters['isElectronic'])){
                    switch ($filters['isElectronic']) {
                        case 'true':
                            $tenders->where('type', 'ELECTRONIC');
                            break;
                            case 'false':
                            $tenders->where('type', 'PAPER');
                            break;
                        default:
                            break;
                    }
                }
                if(isset($filters['procedure']) && is_array($filters['procedure'])){
                    $tenders->whereIn('procedure_type', $filters['procedure']);
                }
            }
            if($filters['type'] === 'private'){
                $category_groups = [];
                if(isset($filters['products']) && is_array($filters['products'])){
                    $category_groups = array_merge($category_groups, $filters['products']);
                }
                if(isset($filters['services']) && is_array($filters['services'])){
                    $category_groups = array_merge($category_groups, $filters['services']);
                }
                if(isset($filters['works']) && is_array($filters['works'])){
                    $category_groups = array_merge($category_groups, $filters['works']);
                }
                $categories = [];
                foreach ($category_groups as $category_group) {
                    $category = Categories::find($category_group);
                    $category_childrens = $category->children;
                    array_push($categories, $category);
                    $categories = array_merge($categories, $category_childrens->toArray());
                    foreach ($category_childrens as $category_group) {
                        $category_childrens = $category_group->children;
                        $categories = array_merge($categories, $category_childrens->toArray());
                    }
                }
                
                $tenders->where(function ($query) use($categories) {
                    for ($i = 0; $i < count($categories); $i++){
                        $query->orwhere('category', 'like',  '%"' . $categories[$i]['id'] .'"%');
                    }      
                });
                if($old_tenders){
                    $old_tenders->where(function ($query) use($categories) {
                        for ($i = 0; $i < count($categories); $i++){
                            $query->orwhere('category', 'like',  '%"' . $categories[$i]['id'] .'"%');
                        }      
                    });
                }
            }
        }

        if((!empty($filters['status'])) && $filters['status'] === 'all'){
            $tenders = $tenders->unionAll($old_tenders);
        }

        return $tenders;
    }

    public function index_old(Request $request){
        ini_set('memory_limit', '-1');
        $vuetable = new EloquentVueTables();
        // return $request['query'];
        $index = $request['index'];
        if($request['query']){
            // $live_tenders = TenderState::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
            //     ->orderBy('created_at', 'DESC')->with('getCpv')->with('organizator')->where('id', $request['tenderId']);
            // $old_tenders = TenderStateArchive::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
            //     ->orderBy('created_at', 'DESC')->with('getCpv')->with('organizator')->where('id', $request['tenderId']);
            // $tenders = $live_tenders->unionAll($old_tenders);
            // $tenders = $tenders->get();

            $tenders = TenderState::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
            ->orderBy('created_at', 'DESC')->with('getCpv')->with('organizator');

            $tenders = $vuetable->get($tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"]]);
            return $this->respondWithPaginationServerTable(TenderResource::collection($tenders['data']), $tenders['count']);
        }
        if($index){
            $filters = $request->all();
        } else {
            $user_id = auth('api')->user()->id;
            $filtersDB = UserFilters::where('user_id', $user_id)->first();
            $type = null;
            $region = null;
            $procedure = null;
            $organizator = null;
            $isElectronic = null;
            $guaranteed = null;
            $status = 'active';
            if($filtersDB){
                if(json_decode($filtersDB->status)){
                    $status = json_decode($filtersDB->status)->value;
                }
                if(json_decode($filtersDB->type)){
                    $type = json_decode($filtersDB->type)->value;
                }
                if(json_decode($filtersDB->region)){
                    foreach (json_decode($filtersDB->region) as $value) {
                        $region[] = $value->id;
                    }
                }
                if(count(json_decode($filtersDB->procedure))){
                    foreach (json_decode($filtersDB->procedure) as $value) {
                        $procedure[] = $value->id;
                    }
                }
                if(count(json_decode($filtersDB->organizator))){
                    foreach (json_decode($filtersDB->organizator) as $value) {
                        $organizator[] = $value->id;
                    }
                }
                if(json_decode($filtersDB->isElectronic)){
                    $isElectronic = json_decode($filtersDB->isElectronic)->value ? 'true' : 'false';
                }
                if(json_decode($filtersDB->guaranteed)){
                    $guaranteed = json_decode($filtersDB->guaranteed)->value ? '1' : '0';
                }
            }
            $filters = [
                "isElectronic" => $isElectronic,
                "procedure" => $procedure,
                "guaranteed" => $guaranteed,
                "region" => $region,
                "type" => $type,
                "organizator" => $organizator,
                "status" => $status,
                "query" => null,
                "limit" => '10',
                "ascending" => '1',
                "page" => '1',
                "byColumn" => '0',
                "competition" => 'true',
                "category" => "onlyMy",
                "products" => null,
                "services" => null,
                "works" => null,
                "favorite" => 'false',
                "tenderId" => null,
                "tenderGroupId" => null,
            ];
        }
        if(!empty($filters['status'])){
            switch ($filters['status']) {
                case 'active':
                    $tenders = TenderState::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
                        ->with('getCpv')->with('organizator');
                    break;
                case 'finished':
                    $tenders = TenderStateArchive::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
                    ->with('getCpv')->with('organizator');
                    break;
                case 'all':
                    $live_tenders = TenderState::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
                        ->with('getCpv')->with('organizator');
                    $old_tenders = TenderStateArchive::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
                        ->with('getCpv')->with('organizator');
                    $tenders = $live_tenders->unionAll($old_tenders);
                    break;
                default:
                    $tenders = TenderState::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
                        ->with('getCpv')->with('organizator');
                    break;
            }
        } else {
            $tenders = TenderState::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
                        ->orderBy('created_at', 'DESC')->with('getCpv')->with('organizator');
        }
        if(empty($request['order'])){
            $tenders->orderBy('created_at', 'DESC');
        } else {
            if($request['order'] === 'byStartDate'){
                $tenders->orderBy('start_date', 'DESC');
            } elseif($request['order'] === 'byEndDate') {
                $tenders->orderBy('end_date', 'ASC');
            }
        }
        $user_id = auth('api')->user()->id;
        if(!empty($filters['favorite']) && $filters['favorite'] === 'true'){
            $old_tenders = TenderStateArchive::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
                                ->orderBy('created_at', 'DESC')->with('getCpv')->with('organizator')->whereHas('favorite', function($query) use ($user_id) {
                                    $query->where('user_id', $user_id);
                                });

            $tenders->whereHas('favorite', function($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->unionAll($old_tenders);

            $tenders = $tenders->unionAll($old_tenders);
            // $tenders = $old_tenders->get();

            // return $this->respondWithPaginationServerTable(TenderResource::collection($tenders), count($tenders));
        } else {
            if(!empty($filters['competition'])){
                $tenders->where('is_competition', $filters['competition'] === 'true' ? 1 : null);
            }
            if(!empty($filters['category'])){
                if($filters['category'] === 'onlyMy'){
                    // $user_cpvs_array = auth('api')->user()->selectedCpvs();
                    // $tenders->whereHas('getCpv', function($query) use ($user_cpvs_array) {
                    //     $query->whereIn('cpv_id', $user_cpvs_array);
                    // });
                    $user_cpvs = array_keys(UserCpvs::where('user_id', $user_id)->get()->keyBy('cpv_id')->toArray());
                    $tenders->whereHas('getCpv', function($query) use ($user_cpvs) {
                        $query->whereIn('cpv_id', $user_cpvs);
                    });
                }
            }
            if(isset($filters['region']) && is_array($filters['region'])){
                $tenders->whereIn('regions', $filters['region']);
            }
            if(isset($filters['organizator']) && is_array($filters['organizator'])){
                $tenders->whereIn('organizer_id', $filters['organizator']);
            }
            if($filters['guaranteed'] === '0' || $filters['guaranteed'] === '1'){
                $tenders->where('guaranteed', $filters['guaranteed']);
            }
            if(!empty($filters['type'])){
                $tenders->where('kind', $filters['type']);
                if($filters['type'] === 'competitive' || $filters['type'] === 'one_person'){
                    $cpv_groups = [];
                    if(isset($filters['products']) && is_array($filters['products'])){
                        $cpv_groups = array_merge($cpv_groups, $filters['products']);
                    }
                    if(isset($filters['services']) && is_array($filters['services'])){
                        $cpv_groups = array_merge($cpv_groups, $filters['services']);
                    }
                    if(isset($filters['works']) && is_array($filters['works'])){
                        $cpv_groups = array_merge($cpv_groups, $filters['works']);
                    }
                    $cpvs = [];
                    foreach ($cpv_groups as $cpv_group) {
                        $cpv = Cpv::find($cpv_group);
                        $cpv_childrens = $cpv->children;
                        array_push($cpvs, $cpv);
                        $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                        foreach ($cpv_childrens as $cpv_group) {
                            $cpv_childrens = $cpv_group->children;
                            $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                            foreach ($cpv_childrens as $cpv_group) {
                                $cpv_childrens = $cpv_group->children;
                                $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                                foreach ($cpv_childrens as $cpv_group) {
                                    $cpv_childrens = $cpv_group->children;
                                    $cpvs = array_merge($cpvs, $cpv_childrens->toArray());
                                }
                            }
                        }
                    }
                    $tenders->where(function ($query) use($cpvs) {
                        for ($i = 0; $i < count($cpvs); $i++){
                            $query->orwhere('cpv', 'like',  '%"' . $cpvs[$i]['id'] .'"%');
                        }      
                    });
                }
                if($filters['type'] === 'competitive'){
                    if(!empty($filters['isElectronic'])){
                        switch ($filters['isElectronic']) {
                            case 'true':
                                $tenders->where('type', 'ELECTRONIC');
                                break;
                                case 'false':
                                $tenders->where('type', 'PAPER');
                                break;
                            default:
                                break;
                        }
                    }
                    if(isset($filters['procedure']) && is_array($filters['procedure'])){
                        $tenders->whereIn('procedure_type', $filters['procedure']);
                    }
                }
                if($filters['type'] === 'private'){
                    $category_groups = [];
                    if(isset($filters['products']) && is_array($filters['products'])){
                        $category_groups = array_merge($category_groups, $filters['products']);
                    }
                    if(isset($filters['services']) && is_array($filters['services'])){
                        $category_groups = array_merge($category_groups, $filters['services']);
                    }
                    if(isset($filters['works']) && is_array($filters['works'])){
                        $category_groups = array_merge($category_groups, $filters['works']);
                    }
                    $categories = [];
                    foreach ($category_groups as $category_group) {
                        $category = Categories::find($category_group);
                        $category_childrens = $category->children;
                        array_push($categories, $category);
                        $categories = array_merge($categories, $category_childrens->toArray());
                        foreach ($category_childrens as $category_group) {
                            $category_childrens = $category_group->children;
                            $categories = array_merge($categories, $category_childrens->toArray());
                        }
                    }
                    $tenders->where(function ($query) use($categories) {
                        for ($i = 0; $i < count($categories); $i++){
                            $query->orwhere('category', 'like',  '%"' . $categories[$i]['id'] .'"%');
                        }      
                    });
                }
            }
        }
        return $tenders;
    }

    public function getUserTendersById(int $user_id){
        $tenders = TenderState::orderBy('created_at', 'DESC')->with('getCpv')->with('organizator');
        $user_cpvs = array_keys(UserCpvs::where('user_id', $user_id)->get()->keyBy('cpv_id')->toArray());
        $tenders->whereHas('getCpv', function($query) use ($user_cpvs) {
            $query->whereIn('cpv_id', $user_cpvs);
        });
        $tenders->where('end_date','>',date("Y-m-d H:i:s"));
        $tenders->where('is_competition', 1);
        return $tenders->get();
    }
 
    public function getFilterOptions(){
       return [
            'procedure_types' => Procedure::all(),
            'regions' => Region::all(),
            'organizators' => Organizator::all(),
            'competitive_product' => Cpv::where([['code', 'like', '%00000'],['type', '1'],['parent_id','!=','0']])->get(),
            'competitive_service' => Cpv::where([['code', 'like', '%00000'],['type', '2'],['parent_id','!=','0']])->get(),
            'competitive_work' => Cpv::where([['code', 'like', '%00000'],['type', '3'],['parent_id','!=','0']])->get(),
            'private_product' => Categories::where('parent', '6')->get(),
            'private_service' => Categories::where('parent', '3')->get(),
            'private_work' => Categories::where('parent', '38')->get(),
            "columns" => TendersTableConfig::where("user_id",auth('api')->user()->id)->first(),
       ];
    }

    public function getTenderFormSelectValues(){
        return [
             'procedure' => Procedure::all(),
             'regions' => Region::all(),
        ];
    }

    public function getByTenderId(int $id){
        $curr_item = TenderState::find($id);
        if(!$curr_item){
            $curr_item = TenderStateArchive::find($id);
        }
        if($curr_item->is_competition){
            $tender = $curr_item;
            $announcements = TenderState::orderBy('created_at', 'DESC')->with('getCpv')->with('getCategory')->where('tender_state_id', $id)->get();
        } else {
            $tender = TenderState::where('id', $curr_item->tender_state_id)->first();
            $announcements = TenderState::orderBy('created_at', 'DESC')->with('getCpv')->with('getCategory')->where('tender_state_id', $curr_item->tender_state_id)->get();
        }
        foreach($announcements as $val){
            $type_name = '';
            switch ($val->type_name) {
                case "ELAH":
                    $type_name  = "Էլեկտրոնային աճուրդի հայտարարություն և հրավեր";
                    break;
                case "EMNH":
                    $type_name  = "Երկփուլ մրցույթի նախաորակավորման հայտարարություն";
                    break;
                case "BMNH":
                    $type_name  = "Բաց մրցույթի նախաորակավորման հայտարարություն";
                    break;
                case "GHNH":
                    $type_name  = "Գնանշման հարցման նախաորակավորման հայտարարություն";
                    break;
                case "PNMNH":
                    $type_name  = "Փակ նպատակային մրցույթի նախաորակավորման հայտարարություն";
                    break;
                case "PPMNH":
                    $type_name  = "Փակ պարբերական մրցույթի նախաորակավորման հայտարարություն և հրավեր";
                    break;
                case "MAGKH":
                    $type_name  = "Մեկ անձից գնումների կատարման հայտարարություն և հրավեր";
                    break;
                case "KMH":
                    $type_name  = "Կնքված պայմանագրի մասին հայտարարություն";
                    break;
                case "HKP":
                    $type_name  = "Հրավերում կատարված փոփոխություններ";
                    break;
                case "OPKKG":
                    $type_name  = "Օտարերկրյա պետությունների կողմից կազմակերպվող գնումներ";
                    break;
                case "MAEH":
                    $type_name  = "Մայր Աթոռ Ս. Էջմիածնի հայտարարություններ";
                    break;
                case "HKKKGVH":
                    $type_name  = "Հանրային կազմակերպությունների կողմից կատարվող գնումների վերաբերյալ հայտարարություններ";
                    break;
                case "BMHH":
                    $type_name  = "Բաց մրցույթի հայտարարություն և հրավեր";
                    break;
                case "GHHH":
                    $type_name  = "Գնանշման հարցման հայտարարություն և հրավեր";
                    break;
                case "PKMH":
                    $type_name  = "Պայմանագիր կնքելու մասին հայտարարություն";
                    break;
                case "CHGYMH":
                    $type_name  = "Չկայացած գնման ընթացակարգի մասին հայտարարություն";
                    break;
                case "HVTPMH":
                    $type_name  = "Հրավերի վերաբերյալ տրամադրված պարզաբանումների մասին հայտարարություն";
                    break;
                case "HBNA":
                    $type_name  = "Հայտերի բացման նիստի արձանագրություններ";
                    break;
                case "HGNA":
                    $type_name  = "Հայտերի գնահատման նիստի արձանագրություններ";
                    break;
            }

            if(!empty($type_name)){
                $val->title = $type_name ;
            }
        }
        return [
            'tender' => $tender,
            'announcements' => $announcements,
        ];
    }

    public function getTenderRows(int $id){
        $rows = TenderStateCpv::where('tender_state_id', $id)->get();
        return TenderRowsResource::collection($rows);
    }

    public function numberToWordArray(Request $request){
        $numbers = $request->input('number');
        $price_word = [];
        $f = new \NumberFormatter("hy", \NumberFormatter::SPELLOUT);
        $price_words = [];
        foreach ($numbers as $key => $price) {
            $price_word = '';
            $price_int = (int)$price;
            $price_luma = ($price - $price_int) * 100;
            $price_luma = round($price_luma);

            $price_int_word = $f->format($price_int);
            $price_int_word = str_replace("-"," ",$price_int_word);

            if($price_luma > 0){
                $price_luma_wird = $f->format($price_luma);
                $price_luma_wird = str_replace("-"," ",$price_luma_wird).' լումա';
            }
            $price_word = $price_int_word;
            if($price_luma > 0){
                $price_word = $price_int_word." դրամ ".$price_luma_wird;
            }

            $mistakes = ["միլիօն", "հարյուր", "իննասուն", 'տասն ', 'տասինը'];
            $fixes   = ["միլիոն", " հարյուր", "իննսուն", 'տաս ', 'տասնինը'];

            $price_word = str_replace($mistakes, $fixes, $price_word);

            $price_words[] = $price_word;
        }

        return $price_words;
    }

    public function numberToWord(Request $request){
        $price = $request->input('number');
        $f = new \NumberFormatter("hy", \NumberFormatter::SPELLOUT);

        $price_word = '';
        $price_int = (int)$price;
        $price_luma = ($price - $price_int) * 100;
        $price_luma = round($price_luma);

        $price_int_word = $f->format($price_int);
        $price_int_word = str_replace("-"," ",$price_int_word);

        if($price_luma > 0){
            $price_luma_wird = $f->format($price_luma);
            $price_luma_wird = str_replace("-"," ",$price_luma_wird).' լումա';
        }
        $price_word = $price_int_word;
        if($price_luma > 0){
            $price_word = $price_int_word." դրամ ".$price_luma_wird;
        }

        $mistakes = ["միլիօն", "հարյուր", "իննասուն", 'տասն ', 'տասինը'];
        $fixes   = ["միլիոն", " հարյուր", "իննսուն", 'տաս ', 'տասնինը'];

        $price_word = str_replace($mistakes, $fixes, $price_word);

        return $price_word;
    }

    // MANAGER FUNCTIONALITY

    public function addTendersTableConfig(Request $request){
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'favorite' => ['required'],
            'invitation' => ['required'],
            'application' => ['required'],
            'type' => ['required'],
            'region' => ['required'],
            'price' => ['required'],
            'opening_finish_date' => ['required'],
            'products' => ['required'],
            'organizator' => ['required'],
            'title' => ['required'],
            'password' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $user_id =  auth('api')->user()->id;
        $tendersTableConfig = TendersTableConfig::where("user_id",$user_id)->first();
        if(empty($tendersTableConfig)){
            $tendersTableConfig = new  TendersTableConfig;
            $tendersTableConfig->user_id = $user_id;
            $tendersTableConfig->favorite = $data['favorite'];
            $tendersTableConfig->invitation = $data['invitation'];
            $tendersTableConfig->application = $data['application'];
            $tendersTableConfig->type = $data['type'];
            $tendersTableConfig->region = $data['region'];
            $tendersTableConfig->price = $data['price'];
            $tendersTableConfig->opening_finish_date = $data['opening_finish_date'];
            $tendersTableConfig->products = $data['products'];
            $tendersTableConfig->organizator = $data['organizator'];
            $tendersTableConfig->title = $data['title'];
            $tendersTableConfig->password = $data['password'];
            $tendersTableConfig->save();

            return response()->json(['error' => false, 'message' => 'data successfully added']);
            
        }else{
            TendersTableConfig::where('user_id', $user_id)
            ->update([
                'favorite' => $data['favorite'],
                'invitation' => $data['invitation'],
                'application' => $data['application'],
                'type' => $data['type'],
                'region' => $data['region'],
                'price' => $data['price'],
                'opening_finish_date' => $data['opening_finish_date'],
                'products' => $data['products'],
                'organizator' => $data['organizator'],
                'title' => $data['title'],
                'password' => $data['password'],
            ]);

            return  response()->json(['error' => false, 'message' => 'data successfully updated']);
        }
    }

    public function managerAddTender(Request $request){
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }

        $isValid = Validator::make($data, [
            'start_date' => ['required'],
            'end_date' => ['required'],
            'guaranteed' => ['required'],
            // 'estimated_file' => ['file'],
            // 'invitation_file' => ['file'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $tenderState = new tenderState;
        
        $cpvCodes = array();
        $tenderState->setTranslation('title', 'hy' , $request->title);
        $user_organisation = auth('api')->user()->organisation;
        $category = ($request->cpvOrCategory == "category") ? json_encode($request->cpv) : 0;
        $organizator = explode(",",$request->organizator);
        $regions = ($request->regions == null) ? 0 : $request->regions;
        $kind = "private";
        $type = ($request->type == null ) ? 0 : $request->type;
        $procedure = ($request->procedure == null ) ? 0 : $request->procedure;
        $passwordTender = ($request->passwordTender == null ) ? " " : $request->passwordTender;
        $tender_state_id = ($request->searchTenderState == null ) ? 0 : $request->searchTenderState;
        $beneficiari = ($request->beneficiari == null ) ? 0 : $request->beneficiari;
        $tenderState->start_date                = $request->start_date;
        $tenderState->end_date                  = $request->end_date;
        $tenderState->cpv                       = $request->cpv;
        $tenderState->state_institution         = 0;
        $tenderState->regions                   = $regions;
        $tenderState->type                      = $type;
        $tenderState->tender_type               = 2;
        $tenderState->is_competition            = 1;
        $tenderState->is_new                    = 1;
        $tenderState->is_closed                 = 1; // OPEN
        $tenderState->estimated                 = $request->estimated;
        $tenderState->password                  = $passwordTender;
        $tenderState->category                  = $category;
        $tenderState->manager_id                = auth('api')->user()->id;
        $tenderState->organizer_id              = 0;
        $tenderState->customer_name             = '«'.$user_organisation->name.'» '.$user_organisation->company_type;
        $tenderState->kind                      = $kind;
        $tenderState->procedure_type            = $procedure;
        $tenderState->guaranteed                = $request->guaranteed === 'true' ? 1 : 0;
        $tenderState->tender_state_id           = $tender_state_id;
        $tenderState->beneficiari               = $beneficiari;


        if(!empty($request->file('estimated_file'))){
            $value = $request->file('estimated_file');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/tender/estimated_file',$fileNameToStore,"publicP");
            $fileURL = config('app.url')."/uploads/".$path;
            $tenderState->estimated_file = $fileURL;
        }

        if(!empty($request->file('invitation_file'))){
            $value = $request->file('invitation_file');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/tender/invitation_file',$fileNameToStore,"publicP");
            $fileURL = config('app.url')."/uploads/".$path;
            $tenderState->invitation_link = $fileURL;
        }
        $tenderState->save();
        $cpvs_parsed = json_decode($request->cpv);
        $cpvs = Cpv::whereIn('id',$cpvs_parsed)->get();

        foreach($cpvs as $cp){
            $tenderStateCpv = new TenderStateCpv();
            $tenderStateCpv->cpv_id = $cp->id;
            $tenderStateCpv->tender_state_id = $tenderState->id;
            $tenderStateCpv->view_id = NULL;
            $tenderStateCpv->cpv_name = NULL;
            $tenderStateCpv->cpv_code = NULL;
            $tenderStateCpv->save(); 
        }

        $tenderState->cpv_codes = json_encode($cpvCodes);
        $tenderState->save();

        $data = new \stdClass();
        $cpvsArray = [];
        $cpvs = json_decode($request->cpv);
        $users = array();
        $data->subject = "Նոր տենդեր iTender համակարգում";
        $cpvUsers = User::select("users.email","users.id","users.email_notifications")
                        ->join("user_cpvs","user_cpvs.user_id","=","users.id")
                        ->whereIn("user_cpvs.cpv_id",$cpvs)
                        ->groupBy("users.email")->get();

        foreach($cpvUsers as $val){
            $users[$val->email]['email'] = $val->email;
            $users[$val->email]['id'] = $val->id;
            $users[$val->email]['email_notifications'] = $val->email_notifications;
        }

        $users =  array_values($users);
        $url = Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
        $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $passwordTender;
        if($request->passwordTender != null){
            $data->subject = "Նոր տենդեր iTender համակարգում: Ծածկագիրը՝ $password";
        }
        $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                        Գնման առարկան` ".htmlentities($request->title)."
                        </div>
                        <div style='display: none; max-height: 0px; overflow: hidden;'>
                        &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                    </div>
                       <p>Պատվիրատուն՝ ".$tenderState->customer_name."</p><br>
                       <p>Գնման առարկան՝ ".htmlentities($request->title)."</p></br>
                       <p>Ծածկագիրը՝ ".$password."</p></br>
                       <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($request->start_date))."</p></br>
                       <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($request->end_date))."</p>
                       <a href = '".$url."'>Տեսնել</a></br>
                       <p>Հարգանքով՝ iTender թիմ</p>";
        ProcessNewTenderAddedToList::dispatch($cpvs, $tenderState, $data);
        $participants_count = 0;
        if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
            foreach($users as $user){
                $data->email = trim($user['email']);
                $filters = UserFilters::where("user_id",$user['id'])->first();
                $email = false;
                if(is_null($filters)){
                    $participants_count++;
                    if($user['email_notifications']){
                        ProcessNewTenderAdded::dispatch($data);
                    }
                    $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $tenderState->customer_name, 'tender_created', $request, $tenderState->customer_name, $password, $url);
                }else{
                    $email = 0;
                    $filterCount = 0;
                    $status = (isset(json_decode($filters->status)->value)) ? json_decode($filters->status)->value : null;
                    $filterKind =  (isset(json_decode($filters->type)->value) ) ? json_decode($filters->type)->value : null;
                    $filterRegions = ( json_decode($filters->region) != "null" ) ? json_decode($filters->region) : null;
                    // $filterTenderType  = (isset(json_decode($filters->status)->value) ) ? json_decode($filters->status)->value : null;
                    // $isElectronic = (isset(json_decode($filters->isElectronic)->value)) ? ((json_decode($filters->isElectronic)->value) ? 'ELECTRONIC' : 'PAPER') : null; 
                    // $guaranteed =  ( isset(json_decode($filters->guaranteed)->value) ) ? ((json_decode($filters->guaranteed)->value) ? "1" : "0") : null;
    
                    if(!is_null($status)){
                        $filterCount++;
                        if($status == 'active' || $status == "all"){
                            $email++;
                        }
                    }
    
                    if(!is_null($filterKind)){
                        $filterCount++;
                        if($filterKind == 'private'){
                            $email++;
                        }
                    }
    
                    if(count($filterRegions)){
                        $filterCount++;
                        foreach($filterRegions as $fr){
                            if($regions == $fr->id){
                                $email++;
                            }
                        }
                    }
    
                    if($email == $filterCount){
                        $participants_count++;
                        if($user['email_notifications']){
                            ProcessNewTenderAdded::dispatch($data);
                        }
                        $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $tenderState->customer_name, 'tender_created', $request, $tenderState->customer_name, $password, $url);
                    }
                }
    
            }
        }
        $tenderState->participants_count = $tenderState->participants_count + $participants_count;
        $tenderState->save();
        return $tenderState;
    }

    public function sendTenderNotification($user_id, $tender_id, $subject, $customer, $type, $request, $ogName, $password, $url)
    {
        // event(new NotificationEvent(292));
        $user = User::find($user_id);
        if($user->telegram_id && $user->telegram_notifications){
            $subject = substr($subject, 0, strpos($subject, ":"));
            $name = gettype($ogName) === 'object' ? $ogName->name : $ogName;

            $content = "*$subject*
    
    Պատվիրատուն՝ *".$name."*
    Գնման առարկան՝ *".$request->title."*
    Ծածկագիրը՝ *".$password."*
    Սկիզբ՝ *".date("Y-m-d H:i",strtotime($request->start_date))."*
    Վերջնաժամկետը՝ *".date("Y-m-d H:i",strtotime($request->end_date))."*
            ";
            Notification::send($user, new SendNotification($user_id, $content, $url));
        }
        // $notification_data = [
        //     'customer' => $customer,
        //     'type' => $type,
        //     'subject' => $subject,
        //     'tender_id' => $tender_id
        // ];
        // $user = User::find($user_id);
        // $user->notify(new TenderCreated($notification_data));
    }

    public function managerEditTender(Request $request){
        $tenderState = TenderState::findOrFail($request->id);
        $tenderState->setTranslation('title', 'hy' , $request->title);

        $cpv = json_encode($request->cpv);

        // return $request->validate(['invitation_file' => 'clamav']);

        $regions = ($request->regions == null) ? 0 : $request->regions;
        $type = ($request->type == null ) ? 0 : $request->type;
        $passwordTender = ($request->passwordTender == null ) ? " " : $request->passwordTender;

        $tenderState->start_date                = $request->start_date;
        $tenderState->end_date                  = $request->end_date;
        $tenderState->cpv                       = $request->cpv;
        $tenderState->regions                   = $regions;
        $tenderState->type                      = $type;
        $tenderState->estimated                 = $request->estimated;
        $tenderState->password                  = $passwordTender;
        $tenderState->guaranteed                = $request->guaranteed === 'true' ? 1 : 0;

        if(!empty($request->file('estimated_file'))){
            $value = $request->file('estimated_file');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/tender/estimated_file',$fileNameToStore,"publicP");

            $tenderState->estimated_file = $fileNameToStore;
        }

        if(!empty($request->file('invitation_file'))){
            $value = $request->file('invitation_file');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/tender/invitation_file',$fileNameToStore,"publicP");
            $fileURL = config('app.url')."/uploads/".$path;
            $tenderState->invitation_link = $fileURL;
        }

        $tenderState->save();

        TenderStateCpv::where("tender_state_id",$tenderState->id)->delete();
        $cpvs = Cpv::whereIn('id',json_decode($request->cpv))->get();
        foreach($cpvs as $cp){
            $tenderStateCpv = new TenderStateCpv();
            $tenderStateCpv->cpv_id = $cp->id;
            $tenderStateCpv->tender_state_id = $tenderState->id;
            $tenderStateCpv->view_id  =  NULL;
            $tenderStateCpv->cpv_name = NULL;
            $tenderStateCpv->cpv_code = NULL;
            $tenderStateCpv->save(); 
        }

        $data = new \stdClass();
        $cpvs = json_decode($request->cpv);
        $users = array();
        $data->subject = "Փոփոխություններ հրապարակված տենդերում";
        $cpvUsers = User::select("users.email","users.id","users.email_notifications")
                        ->join("user_cpvs","user_cpvs.user_id","=","users.id")
                        ->whereIn("user_cpvs.cpv_id",$cpvs)
                        ->groupBy("users.email")
                        ->get();

        foreach($cpvUsers as $val){
            $users[$val->email]['email'] = $val->email;
            $users[$val->email]['id'] = $val->id;
            $users[$val->email]['email_notifications'] = $val->email_notifications;
        }

        $users =  array_values($users);
        $url = Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
        $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $request->passwordTender;
        if($request->passwordTender != null){
            $data->subject = "Փոփոխություններ հրապարակված տենդերում: Ծածկագիրը՝ $password";
        }
        $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                        Գնման առարկան` ".htmlentities($request->title)."
                        </div>
                        <div style='display: none; max-height: 0px; overflow: hidden;'>
                        &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                       </div>
                       <p>Պատվիրատուն՝ ".$tenderState->customer_name."</p><br>
                       <p>Գնման առարկան՝ ".htmlentities($request->title)."</p></br>
                       <p>Ծածկագիրը՝ ".$password."</p></br>
                       <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($request->start_date))."</p></br>
                       <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($request->end_date))."</p>
                       <a href = '".$url."'>Տեսնել</a></br>
                       <p>Հարգանքով՝ iTender թիմ</p>";
        ProcessNewTenderAddedToList::dispatch($cpvs, $tenderState, $data);
        if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
            foreach($users as $user){
                $data->email = $user['email'];
                $filters = UserFilters::where("user_id",$user['id'])->first();
                $email = false;
                if(is_null($filters)) {
                    if($user['email_notifications']){
                        ProcessNewTenderAdded::dispatch($data);
                    }
                    $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $tenderState->customer_name, 'tender_created', $request, $tenderState->customer_name, $password, $url);
                }else{
                    $email = 0;
                    $filterCount = 0;
                    $status = (isset(json_decode($filters->status)->value)) ? json_decode($filters->status)->value : null;
                    $filterKind =  (isset(json_decode($filters->type)->value) ) ? json_decode($filters->type)->value : null;
                    $filterRegions = ( json_decode($filters->region) != "null" ) ? json_decode($filters->region) : null;
                    // $filterTenderType  = (isset(json_decode($filters->status)->value) ) ? json_decode($filters->status)->value : null;
                    // $isElectronic = (isset(json_decode($filters->isElectronic)->value)) ? ((json_decode($filters->isElectronic)->value) ? 'ELECTRONIC' : 'PAPER') : null; 
                    // $guaranteed =  ( isset(json_decode($filters->guaranteed)->value) ) ? ((json_decode($filters->guaranteed)->value) ? "1" : "0") : null;

                    if(!is_null($status)){
                        $filterCount++;
                        if($status == 'active' || $status == "all"){
                            $email++;
                        }
                    }

                    if(!is_null($filterKind)){
                        $filterCount++;
                        if($filterKind == 'private'){
                            $email++;
                        }
                    }

                    if(count($filterRegions)){
                        $filterCount++;
                        foreach($filterRegions as $fr){
                            if($regions == $fr->id){
                                $email++;
                            }
                        }
                    }

                    if($email == $filterCount){
                        if($user['email_notifications']){
                            ProcessNewTenderAdded::dispatch($data);
                        }
                        $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $tenderState->customer_name, 'tender_created', $request, $tenderState->customer_name, $password, $url);
                        // event(new NotificationEvent($user['id']));
                    }
                }
            }
        }
        return $tenderState;
    }

    public function managerGetTender(Request $request){
        $user_organisation_id = auth('api')->user()->id;
        $vuetable = new EloquentVueTables();
        $tenders = TenderState::orderBy('created_at', 'DESC')->with('getCpv')->with('organizator')->where('manager_id', $user_organisation_id);
        $tenders = $vuetable->get($tenders, ['*'], ['title', 'password', 'customer_name'], ["organizator" => ["name"]]);
        return $this->respondWithPaginationServerTable(TenderResource::collection($tenders['data']), $tenders['count']);
    }

    public function managerGetTenderById(Int $tender_id){
        $user_id = auth('api')->user()->id;
        $tender = TenderState::where([['id', $tender_id], ['manager_id', $user_id]])->first();
        return new TenderResource($tender);
    }

    public function managerDeleteTender(Request $request){
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'tender_id' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $user_id = auth('api')->user()->id;
        if(TenderState::where("id",$data['tender_id'])->where("manager_id",$user_id)->delete()){
            return  response()->json(['error' => false, 'message' => "tender successfully deleted" ]);
        }else{
            return  response()->json(['error' => false, 'message' => "something went wrong" ]);
        }

    }
}