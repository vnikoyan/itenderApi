<?php
namespace App\Http\Controllers\Admin\Tender\State;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Tender\TenderState;
use App\Models\Tender\TenderStateArchive;
use App\Models\Tender\TenderStateCpv;
use App\Models\Tender\TenderStateCategory;
use App\Models\Tender\Procedure;
use App\Models\User\User;
use App\Models\Cpv\Cpv;
use App\Models\Translation\Language;
use App\Models\Settings\Ministry;
use App\Models\Tender\Organizator;
use App\Models\Settings\Region;
use App\Models\Settings\UserFilters;
use App\Jobs\ProcessNewTenderAdded;
use App\Events\NotificationEvent;
use App\Models\Settings\StateInstitution;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Tender\State\TenderStoreAndUpdateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tender\UserCorrespondingTenders;
use Illuminate\Support\Facades\Input;
use App\Imports\CpvList;
use App\Models\Settings\Units;
use App\Notifications\TenderCreated;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\SendNotification;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TenderStateController extends AbstractController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:tender');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($type = 3){
        return view('admin.tender.state.index',compact('type'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(TenderState $tenderState){
        $units  = Units::orderBy('id','ASC')->get();
        $regions   = Region::orderBy('id','ASC')->get();
        $organizator = Organizator::orderBy('id','ASC')->get();
        $procedure = Procedure::orderBy('id','ASC')->get();
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.tender.state.add',compact('tenderState','language','organizator','regions','procedure', 'units'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TenderStoreAndUpdateRequest $request,TenderState $tenderState){
        $cpvCodes = array();
        foreach($request->title as $key => $value){
            $tenderState->setTranslation('title', $key , $value);
            // $tenderState->setTranslation('link', $key , $request->link[$key]);
        }
        $cpv = json_encode($request->cpv);
        $category = ($request->cpvOrCategory == "category") ? json_encode($request->cpv) : 0;
        $organizator = explode(",",$request->organizator);
        $regions = ($request->regions == null) ? 0 : $request->regions;
        if($regions == 0){
            $kind = "international";
        }
        if( $regions != 0 && $organizator[1] == "1"){
            $kind = "competitive";
        }        
        if( $regions != 0 &&  $organizator[1] == "2"){
            $kind = "private";
        }

        $request->start_date = date("Y-m-d",strtotime($request->start_date))." ".trim($request->start_time).":00";
        $request->end_date = date("Y-m-d",strtotime($request->end_date))." ".trim($request->end_time).":00";

        $type = ($request->type == null ) ? 0 : $request->type;
        $procedure = ($request->procedure == null ) ? 0 : $request->procedure;
        $passwordTender = ($request->passwordTender == null ) ? " " : $request->passwordTender;
        $is_closed = ($request->is_closed == null) ? 0 : 1 ;
        $tender_state_id = ($request->searchTenderState == null ) ? 0 : $request->searchTenderState;
        $beneficiari = ($request->beneficiari == null ) ? 0 : $request->beneficiari;
        $is_with_model = ($request->is_with_model == null ) ? 0 : $request->is_with_model;
        $tenderState->start_date                = $request->start_date;
        $tenderState->end_date                  = $request->end_date;
        $tenderState->cpv                       = $cpv;
        $tenderState->ministry                  = 0;
        $tenderState->state_institution         = 0;
        $tenderState->regions                   = $regions;
        $tenderState->type                      = $type;
        $tenderState->tender_type               = $organizator[1];
        $tenderState->is_competition            = $request->is_competition;
        $tenderState->is_new                    = $request->is_new;
        $tenderState->is_closed                 = $is_closed;
        $tenderState->estimated                 = $request->estimated;
        $tenderState->password                  = $passwordTender;
        $tenderState->category                  = $category;
        $tenderState->organizer_id              = $organizator[0];
        $tenderState->kind                      = $kind;
        $tenderState->procedure_type            = $procedure;
        $tenderState->guaranteed                = $request->guaranteed;
        $tenderState->tender_state_id           = $tender_state_id;
        $tenderState->beneficiari               = $beneficiari;
        $tenderState->is_with_model             = $is_with_model;

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

        if(!empty($request->file('estimatedPrice'))){
            $value = $request->file('estimatedPrice');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/tender/estimated_price',$fileNameToStore,"publicP");
            $fileURL = config('app.url')."/uploads/".$path;
            $tenderState->estimated_price = $fileURL;
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

        

        if($tender_state_id != 0){
            $cpvs = TenderStateCpv::where("tender_state_id",$tender_state_id)->get();
            foreach($cpvs as $val){
                $tenderStateCpv = new TenderStateCpv();
                $tenderStateCpv->cpv_id = $val->cpv_id;
                $tenderStateCpv->tender_state_id = $tenderState->id;
                $tenderStateCpv->view_id = $val->view_id;
                $tenderStateCpv->cpv_name = $val->cpv_name;
                $tenderStateCpv->cpv_code = $val->cpv_code;
                $tenderStateCpv->save();
            }

            $data =  tenderState::select("cpv","estimated_file","invitation_link","cpv_codes")->where("id",$tender_state_id)->first();
            $cpv = $data->cpv;
            $tenderState->cpv = $data->cpv;
            $tenderState->cpv_codes = $data->cpv_codes;
            $tenderState->invitation_link = $data->invitation_link;
            $tenderState->estimated_file = $data->estimated_file;
            $tenderState->save();
        }
        

        if($cpv != "0" && $tender_state_id == 0){
            TenderStateCpv::where("tender_state_id",$tenderState->id)->delete();
            for($i = 0; $i < $request->input('cpvsCount'); $i++){
                $view_id = "view_id_".$i;
                $cpv_name = "cpv_name_".$i;
                $cpv_id = "cpv_id_".$i;
                $estimated_price = "estimated_price_".$i;
                $cpv_code = "cpv_code_".$i;

                $count = "count_".$i;
                $specification = "specification_".$i;
                $unit = "unit_".$i;
                
                if($request->input($cpv_id) != null){
                    $tenderStateCpv = new TenderStateCpv();
                    $tenderStateCpv->cpv_id = $request->input($cpv_id);
                    $tenderStateCpv->tender_state_id = $tenderState->id;
                    $tenderStateCpv->view_id = $request->input($view_id);
                    $tenderStateCpv->cpv_name = $request->input($cpv_name);
                    $tenderStateCpv->cpv_code = $request->input($cpv_code);

                    $tenderStateCpv->count = $request->input($count);
                    $tenderStateCpv->specification = $request->input($specification);
                    $tenderStateCpv->unit = $request->input($unit);

                    $tenderStateCpv->estimated_price = $request->input($estimated_price);
                    if( (int) round($request->input($estimated_price)) == 0 ){
                        $tenderStateCpv->is_condition = 1;
                    }
                    $tenderStateCpv->save();
                }
            }
        }
        for($i = 0; $i < $request->input('notexistCpvsCount'); $i++){
            $tenderStateCpv = new TenderStateCpv();
            if( !empty($request->input("cpv_code_ne_".$i."")) && 
                !empty($request->input("cpv_name_ne_".$i."")) && !empty($request->input("view_id_ne_".$i.""))){
                $tenderStateCpv->cpv_id = 0;
                $tenderStateCpv->tender_state_id = $tenderState->id;
                $tenderStateCpv->view_id = $request->input("view_id_ne_".$i."");
                $tenderStateCpv->cpv_name = $request->input("cpv_name_ne_".$i."");
                $tenderStateCpv->cpv_code = $request->input("cpv_code_ne_".$i."");
                $tenderStateCpv->estimated_price = $request->input("estimated_price_ne_".$i."");
                if( (int) round($request->input("estimated_price_ne_".$i."")) == 0 ){
                    $tenderStateCpv->is_condition = 1;
                }
                $tenderStateCpv->save();
                $cpvCodes[] = $request->input("cpv_code_ne_".$i."");
            }
        }

        if($tender_state_id != 0){
            $cpvCodes = $data->cpv_codes;   
        }else{
            $tenderState->cpv_codes = json_encode($cpvCodes);
        }

        $tenderState->save();
        if($category != "0"){
            foreach($request->cpv as $key => $value ){
                $tenderStateCategory = new TenderStateCategory();
                $tenderStateCategory->category_id = $value;
                $tenderStateCategory->tender_state_id = $tenderState->id;
                $tenderStateCategory->save();
            }
        }
        $data = new \stdClass();
        $cpvsArray = [];
        $cpvs = json_decode($cpv);
        // foreach ($cpvs as $cpv) {
        //     $currCpv = Cpv::find($cpv);
        //     $currCpv->getParents($cpvsArray);
        //     $currCpv->getChildren($cpvsArray);
        // }
        // $cpvsArray = array_values(array_unique($cpvsArray, SORT_REGULAR));
        $users = array();
        $data->subject = "Նոր տենդեր iTender համակարգում";
        $cpvUsers = User::select("users.email","users.id","users.email_notifications")
                        ->join("user_cpvs","user_cpvs.user_id","=","users.id")
                        ->whereIn("user_cpvs.cpv_id",$cpvs)
                        ->join("order","order.user_id","=","users.id")
                        ->where("order.package_id","!=",1)
                        ->where("order.type","ACTIVE")
                        ->whereDate("order.end_date",">=",date("Y-m-d"))
                        ->groupBy("users.email")
                        ->get();
        $searchableTenderUser = TenderStateCpv::select("users.email","users.id","users.email_notifications")
                                              ->join("tender_state","tender_state.id","=","tender_state_cpv.tender_state_id")
                                              ->where("tender_state.id",$tender_state_id)
                                              ->join("user_cpvs","user_cpvs.cpv_id","=","tender_state_cpv.cpv_id")
                                              ->join("users","users.id","=","user_cpvs.user_id")
                                              ->join("order","order.user_id","=","users.id")
                                              ->where("order.package_id","!=",1)
                                              ->where("order.type","ACTIVE")
                                              ->whereDate("order.end_date",">=",date("Y-m-d"))
                                              ->groupBy("users.email")
                                              ->get();

        foreach($cpvUsers as $val){
            $users[$val->email]['email'] = $val->email;
            $users[$val->email]['id'] = $val->id;
            $users[$val->email]['email_notifications'] = $val->email_notifications;
        }
        foreach($searchableTenderUser as $val){
            $users[$val->email]['email'] = $val->email;
            $users[$val->email]['id'] = $val->id;
            $users[$val->email]['email_notifications'] = $val->email_notifications;
        }


        $users =  array_values($users);
        foreach($users as $user){
            $url = \Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
            $data->email = trim($user['email']);
            $ogName = Organizator::where("id",$organizator[0])->select("name")->first();
            $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $passwordTender;
            if($request->passwordTender != null){
                $data->subject = "Նոր տենդեր iTender համակարգում: Ծածկագիրը՝ $password";
            }
            $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                                Գնման առարկան` ".htmlentities($request->title['hy'])."
                            </div>
                            <div style='display: none; max-height: 0px; overflow: hidden;'>
                                &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                            </div>
                           <p>Պատվիրատուն՝ ".htmlentities($ogName->name)."</p><br>
                           <p>Գնման առարկան՝ ".htmlentities($request->title['hy'])."</p></br>
                           <p>Ծածկագիրը՝ ".$password."</p></br>
                           <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($request->start_date))."</p></br>
                           <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($request->end_date))."</p>
                           <a href = '".$url."'>Տեսնել</a></br>
                           <p>Հարգանքով՝ iTender թիմ</p>";

            $filters = UserFilters::where("user_id",$user['id'])->first();
            if(!$filters) {
                UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                if($user['email_notifications']){
                    if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                        ProcessNewTenderAdded::dispatch($data);
                        $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $ogName->name, 'tender_created', $request, $ogName, $password, $url);
                    }
                }
            }else{
                $email = 0;
                $filterCount = 0;
                $filerKind =  (isset(json_decode($filters->type)->value) ) ? json_decode($filters->type)->value : null;
                $filterProcedure = ( json_decode($filters->procedure) != "null" && count(json_decode($filters->procedure)) !== 0 ) ? json_decode($filters->procedure) : null; 
                $filterRegions = ( json_decode($filters->region) != "null" && count(json_decode($filters->region)) !== 0) ? json_decode($filters->region) : null;
                $filterOrganizator = ( json_decode($filters->organizator) != "null" && count(json_decode($filters->organizator)) !== 0) ? json_decode($filters->organizator) : null;
                $filterTenderType  = ( isset(json_decode($filters->status)->value) ) ? json_decode($filters->status)->value : null;
                $isElectronic = (isset(json_decode($filters->isElectronic)->value)) ? ((json_decode($filters->isElectronic)->value) ? 'ELECTRONIC' : 'PAPER') : null; 
                $guaranteed =  ( isset(json_decode($filters->guaranteed)->value) ) ? ((json_decode($filters->guaranteed)->value) ? "1" : "0") : null;

                (isset(json_decode($filters->type)->value) ) ? $filterCount++ : $filterCount ;
                (json_decode($filters->procedure) != "null" && count(json_decode($filters->procedure)) !== 0 ) ? $filterCount++ : $filterCount; 
                (json_decode($filters->region) != "null" && count(json_decode($filters->region)) !== 0) ? $filterCount++ : $filterCount;
                (json_decode($filters->organizator) != "null" && count(json_decode($filters->organizator)) !== 0) ? $filterCount++ : $filterCount;
                (isset(json_decode($filters->status)->value) ) ? $filterCount++ : $filterCount;
                (isset(json_decode($filters->isElectronic)->value)) ? $filterCount++ : $filterCount; 
                (isset(json_decode($filters->guaranteed)->value) ) ? $filterCount++ : $filterCount;

                if(!is_null($filerKind)){
                    if($filerKind == $kind){
                        $email++;
                    }
                }

                if(!is_null($filterProcedure)){
                    foreach($filterProcedure as $fp){
                        if($procedure == $fp->id){
                            $email++;  
                        }
                    }
                }

                if(!is_null($filterRegions)){
                    foreach($filterRegions as $fr){
                        if($regions == $fr->id){
                            $email++;
                        }
                    }
                }

                if(!is_null($filterOrganizator)){
                    foreach($filterOrganizator as $fo){
                        if($fo->id == $organizator[0]){
                            $email++;  
                        }
                    }
                }

                if($isElectronic == $type){
                    $email++;  
                }

                if( $guaranteed == $request->guaranteed ){
                    $email++; 
                }

                if($filterTenderType == "active" && strtotime($request->end_date) > strtotime( date("Y-m-d H:i:s") )){
                    $email++;
                }

                if($filterTenderType == "finished" && strtotime($request->end_date) < strtotime( date("Y-m-d H:i:s") )){
                    $email++;
                }

                if($filterTenderType == "all"){
                    $email = $filterCount;
                }
                if($email == $filterCount){
                    UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                    if($user['email_notifications']){
                        if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                            ProcessNewTenderAdded::dispatch($data);
                            $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $ogName->name, 'tender_created', $request, $ogName, $password, $url);
                        }
                    }
                }
            }
        }
        if(!$request->is_competition){
            return redirect("/admin/tender_state/4");
        }
        return redirect("/admin/tender_state");

    }

    public function sendTenderNotification($user_id, $tender_id, $subject, $customer, $type, $request, $ogName, $password, $url)
    {
        // event(new NotificationEvent(292));
        $user = User::find($user_id);
        $subject = substr($subject, 0, strpos($subject, ":"));

        if($user->telegram_id && $user->telegram_notifications){
            $content = "*$subject*
    
Պատվիրատուն՝ *".$customer."*
Գնման առարկան՝ *".$request->title['hy']."*
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
        // $user->notify(new TenderCreated($notification_data));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $units  = Units::orderBy('id','ASC')->get();
        $cpvAttributes = TenderStateCpv::select("tender_state_cpv.id as rowId", 'tender_state_cpv.*')->where('tender_state_id',$id)->with('statistics')->get();
        $notExistsCpvs = TenderStateCpv::where('tender_state_id',$id)->where('cpv_id',0)->with('statistics')->get();
        $regions   = Region::orderBy('id','ASC')->get();
        $tenderState = TenderState::find($id);
        if(!$tenderState){
            $tenderState = TenderStateArchive::find($id);
        }
        $organizator = Organizator::orderBy('id','ASC')->get();
        $procedure = Procedure::orderBy('id','ASC')->get();
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        $cpvsData = TenderStateCpv::select(
            "cpv.code",
            "tender_state_cpv.id",
            "tender_state_cpv.estimated_price",
            "tender_state_cpv.cpv_id",
            "tender_state_cpv.view_id",
            "tender_state_cpv.cpv_name",
            "tender_state_cpv.cpv_code",
            "tender_state_cpv.unit",
            "tender_state_cpv.count",
            "tender_state_cpv.specification")
            ->leftJoin("cpv","cpv.id","=","tender_state_cpv.cpv_id")
            ->where("tender_state_id",$id)
            ->orderBy("tender_state_cpv.id","ASC")
            ->with("statistics")->with("cpvData")->get();
        $tenderStateSearch = TenderState::select("td.password","organizator.name","td.id")->join("tender_state as td","td.id","=","tender_state.tender_state_id")->join("organizator","organizator.id","=","td.organizer_id")->where("tender_state.id",$id)->first();
        return view('admin.tender.state.edit',compact('tenderState','language','organizator','tenderStateSearch','regions','procedure','cpvAttributes','notExistsCpvs', 'units', 'cpvsData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request,$id){
        $tenderState = TenderState::find($id);
        if(!$tenderState){
            $tenderState = TenderStateArchive::find($id);
        }
        // $request->file('estimated_file')->store('','google');
        foreach($request->title as $key => $value){
            $tenderState->setTranslation('title', $key , $value);
            // $tenderState->setTranslation('link', $key , $request->link[$key]);
        }
        $organizator = explode(',',$request->organizator);
        $cpv = json_encode($request->cpv);

        $category = ($request->cpvOrCategory == "category") ? json_encode($request->cpv) : 0;
        $regions = ($request->regions == null) ? 0 : $request->regions;
        if($regions == 0){
            $kind = "international";
        }
        if( $regions != 0 && $organizator[1] == "1"){
            $kind = "competitive";
        }        
        if( $regions != 0 &&  $organizator[1] == "2"){
            $kind = "private";
        }
        $request->start_date = date("Y-m-d",strtotime($request->start_date))." ".trim($request->start_time).":00";
        $request->end_date = date("Y-m-d",strtotime($request->end_date))." ".trim($request->end_time).":00";
        
        $type = ($request->type == null ) ? 0 : $request->type;
        $is_closed = ($request->is_closed == null) ? 0 : 1 ;
        $tenderState->start_date                = $request->start_date;
        $tenderState->end_date                  = $request->end_date;
        $tenderState->cpv                       = $cpv;
        $tenderState->regions                   = $regions;
        $tenderState->type                      = $request->type;
        $tenderState->tender_type               = $organizator[1];
        $tenderState->is_million10              = $request->is_million10;
        $tenderState->is_competition            = $request->is_competition;
        $tenderState->is_new                    = $request->is_new;
        $tenderState->is_closed                 = $is_closed;
        $tenderState->estimated                 = $request->estimated;
        $tenderState->password                  = $request->passwordTender;
        $tenderState->category                  = $category;
        $tenderState->organizer_id              = $organizator[0];
        $tenderState->kind                      = $kind;
        $tenderState->procedure_type            = $request->procedure;
        $tenderState->guaranteed                = $request->guaranteed;
        $tenderState->beneficiari               = $request->beneficiari;
        $tenderState->is_with_model             = $request->is_with_model;

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

        if(!empty($request->file('estimatedPrice'))){
            $value = $request->file('estimatedPrice');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $path = $value->storeAs('/tender/estimated_price',$fileNameToStore,"publicP");
            $fileURL = config('app.url')."/uploads/".$path;
            $tenderState->estimated_price = $fileURL;
        }

        $tenderState->save();

        TenderStateCpv::where("tender_state_id",$tenderState->id)->delete();
        for($i = 0; $i < $request->input('cpvsCount'); $i++){
                $view_id = "view_id_".$i;
                $cpv_name = "cpv_name_".$i;
                $cpv_id = "cpv_id_".$i;
                $cpv_code = "cpv_code_".$i;
                $estimated_price = "estimated_price_".$i;

                $count = "count_".$i;
                $specification = "specification_".$i;
                $unit = "unit_".$i;

                if($request->input($cpv_id) != null){
                    $tenderStateCpv = new TenderStateCpv();
                    $tenderStateCpv->cpv_id = $request->input($cpv_id);
                    $tenderStateCpv->tender_state_id = $tenderState->id;
                    $tenderStateCpv->view_id = $request->input($view_id);
                    $tenderStateCpv->cpv_name = $request->input($cpv_name);
                    $tenderStateCpv->cpv_code = $request->input($cpv_code);

                    $tenderStateCpv->count = $request->input($count);
                    $tenderStateCpv->specification = $request->input($specification);
                    $tenderStateCpv->unit = $request->input($unit);

                    $tenderStateCpv->estimated_price = $request->input($estimated_price);
                    if( (int) round($request->input($estimated_price)) == 0 ){
                        $tenderStateCpv->is_condition = 1;
                    }
                    $tenderStateCpv->save();
                }
        }
        
        for($i = 0; $i < $request->input('notexistCpvsCount'); $i++){
            $tenderStateCpv = new TenderStateCpv();
            if( !empty($request->input("cpv_code_ne_".$i."")) && 
                !empty($request->input("cpv_name_ne_".$i."")) && !empty($request->input("view_id_ne_".$i.""))){
                $tenderStateCpv->cpv_id = 0;
                $tenderStateCpv->tender_state_id = $tenderState->id;
                $tenderStateCpv->view_id = $request->input("view_id_ne_".$i."");
                $tenderStateCpv->cpv_name = $request->input("cpv_name_ne_".$i."");
                $tenderStateCpv->cpv_code = $request->input("cpv_code_ne_".$i."");
                $tenderStateCpv->estimated_price = $request->input("estimated_price_ne_".$i."");
                if( (int) round($request->input("estimated_price_ne_".$i."")) == 0 ){
                    $tenderStateCpv->is_condition = 1;
                }
                $tenderStateCpv->save();
                $cpvCodes[] = $request->input("cpv_code_ne_".$i."");
            }
        }
        if(!empty($cpvCodes)){
            $tenderState->cpv_codes = json_encode($cpvCodes);
            $tenderState->save();
        }

        $data = new \stdClass();
        $cpvsArray = [];
        $cpvs = json_decode($cpv);
        // foreach ($cpvs as $cpv) {
        //     $currCpv = Cpv::find($cpv);
        //     $currCpv->getParents($cpvsArray);
        //     $currCpv->getChildren($cpvsArray);
        // }
        // $cpvsArray = array_values(array_unique($cpvsArray, SORT_REGULAR));
        $users = array();
        $users = array_values($users);
        $data->subject = "Փոփոխություններ հրապարակված տենդերում";
        $cpvUsers = User::select("users.email","users.id","users.email_notifications")
                        ->join("user_cpvs","user_cpvs.user_id","=","users.id")
                        ->whereIn("user_cpvs.cpv_id",$cpvs)
                        ->join("order","order.user_id","=","users.id")
                        ->where("order.package_id","!=",1)
                        ->where("order.type","ACTIVE")
                        ->whereDate("order.end_date",">=",date("Y-m-d"))
                        ->groupBy("users.email")
                        ->get();
        foreach($cpvUsers as $val){
            $users[$val->email]['email'] = $val->email;
            $users[$val->email]['id'] = $val->id;
            $users[$val->email]['email_notifications'] = $val->email_notifications;
        }

        $users =  array_values($users);


        foreach($users as $user){
            $url = \Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
            $data->email = $user['email'];
            $ogName = Organizator::where("id",$organizator[0])->select("name")->first();
            $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $request->passwordTender;
            if($request->passwordTender != null){
                $data->subject = "Փոփոխություններ հրապարակված տենդերում: Ծածկագիրը՝ $password";
            }
            $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                            Գնման առարկան` ".htmlentities($request->title['hy'])."
                            </div>
                            <div style='display: none; max-height: 0px; overflow: hidden;'>
                            &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                           </div>
                           <p>Պատվիրատուն՝ ".htmlentities($ogName->name)."</p><br>
                           <p>Գնման առարկան՝ ".htmlentities($request->title['hy'])."</p></br>
                           <p>Ծածկագիրը՝ ".$password."</p></br>
                           <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($request->start_date))."</p></br>
                           <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($request->end_date))."</p>
                           <a href = '".$url."'>Տեսնել</a></br>
                           <p>Հարգանքով՝ iTender թիմ</p>";   

            $filters = UserFilters::where("user_id",$user['id'])->first();
            $email = false;
            if(!$filters) {
                UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                if($user['email_notifications']){
                    if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                        ProcessNewTenderAdded::dispatch($data);
                    }
                }
                $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $ogName->name, 'tender_edited', $request, $ogName, $password, $url);
            }else{
                $email = 0;
                $filterCount = 0;
                $filerKind =  (isset(json_decode($filters->type)->value) ) ? json_decode($filters->type)->value : null;
                $filterProcedure = ( json_decode($filters->procedure) != "null" && count(json_decode($filters->procedure)) !== 0 ) ? json_decode($filters->procedure) : null; 
                $filterRegions = ( json_decode($filters->region) != "null" && count(json_decode($filters->region)) !== 0) ? json_decode($filters->region) : null;
                $filterOrganizator = ( json_decode($filters->organizator) != "null" && count(json_decode($filters->organizator)) !== 0) ? json_decode($filters->organizator) : null;
                $filterTenderType  = ( isset(json_decode($filters->status)->value) ) ? json_decode($filters->status)->value : null;
                $isElectronic = (isset(json_decode($filters->isElectronic)->value)) ? (json_decode($filters->isElectronic)->value) ? 'ELECTRONIC' : 'PAPER' : null; 
                $guaranteed =  ( isset(json_decode($filters->guaranteed)->value) ) ? (json_decode($filters->guaranteed)->value) ? "1" : "0" : null;
                
                (isset(json_decode($filters->type)->value) ) ? $filterCount++ : $filterCount ;
                (json_decode($filters->procedure) != "null" && count(json_decode($filters->procedure)) !== 0 ) ? $filterCount++ : $filterCount; 
                (json_decode($filters->region) != "null" && count(json_decode($filters->region)) !== 0) ? $filterCount++ : $filterCount;
                (json_decode($filters->organizator) != "null" && count(json_decode($filters->organizator)) !== 0) ? $filterCount++ : $filterCount;
                (isset(json_decode($filters->status)->value) ) ? $filterCount++ : $filterCount;
                (isset(json_decode($filters->isElectronic)->value)) ? $filterCount++ : $filterCount; 
                (isset(json_decode($filters->guaranteed)->value) ) ? $filterCount++ : $filterCount;

                if(!is_null($filerKind)){
                    if($filerKind == $kind){
                        $email++;
                    }
                }

                if(!is_null($filterProcedure)){
                    foreach($filterProcedure as $fp){
                        if($request->procedure == $fp->id){
                            $email++;  
                        }
                    }
                }

                if(!is_null($filterRegions)){
                    foreach($filterRegions as $fr){
                        if($regions == $fr->id){
                            $email++;
                        }
                    }
                }

                if(!is_null($filterOrganizator)){
                    foreach($filterOrganizator as $fo){
                        if($fo->id == $organizator[0]){
                            $email++;  
                        }
                    }
                }

                if($isElectronic == $type){
                    $email++;  
                }

                if( $guaranteed == $request->guaranteed ){
                    $email++; 
                }

                if($filterTenderType == "active" && strtotime($request->end_date) > strtotime( date("Y-m-d H:i:s") )){
                    $email++;
                }

                if($filterTenderType == "finished" && strtotime($request->end_date) < strtotime( date("Y-m-d H:i:s") )){
                    $email++;
                }

                if($filterTenderType == "all"){
                    $email = $filterCount;
                }
                if($email == $filterCount){
                    UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                    if($user['email_notifications']){
                        if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                            ProcessNewTenderAdded::dispatch($data);
                        }
                    }
                    $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $ogName->name, 'tender_edited', $request, $ogName, $password, $url);
                }
            }
        }

        if(!$request->is_competition){  
            return redirect("/admin/tender_state/4");
        }
        return redirect("/admin/tender_state");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        $tenderState = TenderState::find($id);
        if(!$tenderState){
            $tenderState = TenderStateArchive::find($id);
        }
        $tenderState->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData($type, Request $request){
        ini_set('memory_limit', -1);
        $orderBy = $request->order[0]['dir'];

        $query = $request->search['value'];

        

        $old_tenders = TenderStateArchive::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
            ->orderBy('id',$orderBy)
            ->where("manager_id",0)
            ->with("organizator");

        if($query){
            $old_tenders->where('password', 'LIKE', "%{$query}%");
        }

        $tender_state = TenderState::select('id','is_archived','title','link','start_date','end_date','cpv','ministry','state_institution','regions','type','tender_type','is_million10','is_competition','is_new','is_closed','estimated','estimated_file','customer_name','password','created_at','updated_at','invitation_link','category','organizer_id','kind','procedure_type','guaranteed','contract_html','cpv_codes','one_person_organize_id','tender_state_id','type_name','beneficiari','manager_id','estimated_price','participants_count','is_with_model')
            ->where("manager_id",0)
            ->with("organizator");

        $tender_state = $tender_state->unionAll($old_tenders)->orderBy('id',$orderBy);

        if($query){
            $tableData = Datatables::of(
                $tender_state->where('password', 'LIKE', "%{$query}%"));
            
        }
            
        if($type == 4){
            $tableData = Datatables::of(
                $tender_state->where("is_competition","=",NULL));
        }else{
            $tableData = Datatables::of(
                $tender_state->where("is_competition","=",1));
        }
        
        return $tableData->addColumn('title', function ($tenderState) {
                    return $tenderState->title;
                })->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function fileDownload($id){
        $tenderState = TenderState::find($id);
        if(!$tenderState){
            $tenderState = TenderStateArchive::find($id);
        }
        return \Storage::download("public/info/".$tenderState->file);
    }

    public function getTenderStateByPassword(Request $request){
        $live_tenders = TenderState::select("tender_state.*","tender_state.title as tdName","organizator.name","tender_state.id","tender_state.password")
                           ->join("organizator","organizator.id","=","tender_state.organizer_id")
                           ->where("tender_state.password","LIKE","%".$request->input('password')."%")
                           ->where("tender_state.is_competition", 1 )->get();


        $old_tenders = TenderStateArchive::select("tender_state_archive.*","tender_state_archive.title as tdName","organizator.name","tender_state_archive.id","tender_state_archive.password")
                ->join("organizator","organizator.id","=","tender_state_archive.organizer_id")
                ->where("tender_state_archive.password","LIKE","%".$request->input('password')."%")
                ->where("tender_state_archive.is_competition", 1 )->get();
        
        $collection = collect([$live_tenders, $old_tenders]);

        $data = $collection->collapse();
        return json_decode($data);

    }

    public function uploadEstimatedPrice(Request $request){
        if($request->estimatedPrice->getClientOriginalExtension() == "xlsx"){

        }else{
            return  response()->json(['error' => true, 'message' => 'խնդրում ենք վերբեռնել միայն xlsx ֆորմատի ֆայլեր']);
        }

        $res = array();
        $cpvCodes = $request->get('cpvCodes');
        $cpvCodes = json_decode($cpvCodes);
		$rows = \Excel::toArray(new CpvList, $request->file('estimatedPrice'))[0];
        unset($rows[0]);
        foreach($rows as $row){
            foreach($cpvCodes as $cpv){
                $cpv_code = $row[1];
                if(trim($cpv) == trim($cpv_code)){
                    $res[$cpv]['price'] = $row[8];
                    $res[$cpv]['code'] = $cpv;
                }
            }
        }
        return  response()->json($res);
    }

    public function showManagersTenders(Request $request){
        return view('admin.tender.state.managerTenders');
    }

    public function getManagersTenders(Request $request){
        $orderBy = $request->order[0]['dir'];

        $tableData =  Datatables::of(TenderState::select("tender_state.*")->where("is_competition","=",1)->where("manager_id","!=",0)->orderBy('tender_state.id',$orderBy)->with("getCpv"));

        return $tableData->addColumn('action', function ($tenderState) {
                     return '<a href="/admin/manager/tender_state/edit/'.$tenderState->id.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                             <a href="/admin/manager/tender_state/delete/'.$tenderState->id.'"   class="btn btn-xs btn-danger waves-effect waves-light"><i class="fa fa-trash"></i> Ջնջել</a>';
                })->addColumn('ogName', function ($tenderState) {
                    return $tenderState->customer_name;
                })->addColumn('title', function ($tenderState) {
                    return $tenderState->title;
                })->filterColumn('title', function($query, $keyword) {
                    $sql = "tender_state.title like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })->filterColumn('password', function($query, $keyword) {
                    $sql = "tender_state.password like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })->filterColumn('ogName', function($query, $keyword) {
                    $sql = "tender_state.customer_name like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })->addColumn('categories', function ($tenderState) {
                    $category = "";
                    $category .= "[ ";
                    foreach($tenderState->getCategory as $key => $value){
                        if(isset($value->category->name)){
                            $category .= $value->category->name . " | ";
                        }
                    }
                    $category .= " ]";

                    return $category;
                 })->addColumn('cpv', function ($tenderState) {
                    $cpv = "";
                    $cpv .= "[ ";
                    foreach($tenderState->getCpv as $key => $value){
                        if(isset($value->cpv->name)){
                         $cpv .= $value->cpv->name . " | ";
                        }
                    }
                    $cpv .= " ]";

                    return $cpv;
                 })->filterColumn('cpv', function($query, $keyword) {
                    $query->whereHas('getCpv', function ($q) use ($keyword){
                        $q->whereHas('cpv', function ($que) use ($keyword){
                            $que->where('name', 'like', '%'.$keyword.'%');
                        });
                    });
                })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }

    public function adminEditManagerTender($id){

        $tenderState  = TenderState::where("id",$id)->first();
        $cpvAttributes = TenderStateCpv::where('tender_state_id',$id)->join("cpv","cpv.id","=","tender_state_cpv.cpv_id")->get();
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        $regions   = Region::orderBy('id','ASC')->get();
        return view('admin.tender.state.managerEditTenders',compact('tenderState','cpvAttributes','language','regions','id'));

    }

    public function adminUpdateManagerTender(Request $request){
        $tenderState = TenderState::find($request->tender_id);
        if(!$tenderState){
            $tenderState = TenderStateArchive::find($request->tender_id);
        }
        $tenderState->setTranslation('title', 'hy' , $request->title);
        $cpv = json_encode($request->cpv);
        $regions = ($request->regions == null) ? 0 : $request->regions;
        $type = ($request->type == null ) ? 0 : $request->type;
        $passwordTender = ($request->passwordTender == null ) ? " " : $request->passwordTender;
        $tenderState->start_date                = $request->start_date." ".$request->start_time.":00";
        $tenderState->end_date                  = $request->end_date." ".$request->end_time.":00";
        $tenderState->cpv                       = $cpv;
        $tenderState->regions                   = $regions;
        $tenderState->type                      = $type;
        $tenderState->estimated                 = $request->estimated;
        $tenderState->password                  = $passwordTender;
        $tenderState->guaranteed                = $request->guaranteed;
        
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
        
        TenderStateCpv::where("tender_state_id",$tenderState->id)->delete();
        for($i = 0; $i < $request->input('cpvsCount'); $i++){
                $view_id = "view_id_".$i;
                $cpv_name = "cpv_name_".$i;
                $cpv_id = "cpv_id_".$i;
                $cpv_code = "cpv_code_".$i;
                $estimated_price = "estimated_price_".$i;
                if($request->input($cpv_id) != null){
                    $tenderStateCpv = new TenderStateCpv();
                    $tenderStateCpv->cpv_id = $request->input($cpv_id);
                    $tenderStateCpv->tender_state_id = $tenderState->id;
                    $tenderStateCpv->view_id = $request->input($view_id);
                    $tenderStateCpv->cpv_name = $request->input($cpv_name);
                    $tenderStateCpv->cpv_code = $request->input($cpv_code);
                    $tenderStateCpv->estimated_price = $request->input($estimated_price);
                    if( (int) round($request->input($estimated_price)) == 0 ){
                        $tenderStateCpv->is_condition = 1;
                    }
                    $tenderStateCpv->save();
                }
        }

        $data = new \stdClass();
        $cpvs = json_decode($cpv);
        $users = array();
        $data->subject = "Փոփոխություններ հրապարակված տենդերում";
        $cpvUsers = User::select("users.email","users.id","users.email_notifications")
                        ->join("user_cpvs","user_cpvs.user_id","=","users.id")
                        ->whereIn("user_cpvs.cpv_id",$cpvs)
                        ->join("order","order.user_id","=","users.id")
                        ->where("order.package_id","!=",1)
                        ->where("order.type","ACTIVE")
                        ->whereDate("order.end_date",">=",date("Y-m-d"))
                        ->groupBy("users.email")
                        ->get();

        foreach($cpvUsers as $val){
            $users[$val->email]['email'] = $val->email;
            $users[$val->email]['id'] = $val->id;
            $users[$val->email]['email_notifications'] = $val->email_notifications;
        }

        $users =  array_values($users);
        foreach($users as $user){
            $url = \Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
            $data->email = $user['email'];
            $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $request->passwordTender;
            if($request->passwordTender != null){
                $data->subject = "Փոփոխություններ հրապարակված տենդերում: Ծածկագիրը՝ $password";
            }
            $title = $request->title;
            $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                            Գնման առարկան` ".htmlentities($title)."
                            </div>
                            <div style='display: none; max-height: 0px; overflow: hidden;'>
                            &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                           </div>
                           <p>Պատվիրատուն՝ ".$tenderState->customer_name."</p><br>
                           <p>Գնման առարկան՝ ".htmlentities($title)."</p></br>
                           <p>Ծածկագիրը՝ ".$password."</p></br>
                           <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($request->start_date))."</p></br>
                           <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($request->end_date))."</p>
                           <a href = '".$url."'>Տեսնել</a></br>
                           <p>Հարգանքով՝ iTender թիմ</p>";   

                        $filters = UserFilters::where("user_id",$user['id'])->first();
                        $email = false;
                        if(is_null($filters)) {
                            UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                            if($user['email_notifications']){
                                if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                                    ProcessNewTenderAdded::dispatch($data);
                                    $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $tenderState->customer_name, 'tender_edited', $request, $ogName, $password, $url);
                                }
                            }
                        }else{
                            $status = (isset(json_decode($filters->status)->value)) ? json_decode($filters->status)->value : null;
                            $filerKind =  (isset(json_decode($filters->type)->value) ) ? json_decode($filters->type)->value : null;
                            $filterProcedure = ( (json_decode($filters->procedure)) != "null") ? json_decode($filters->procedure) : null; 
                            $filterRegions = ( (json_decode($filters->region)) != "null") ? json_decode($filters->region) : null;
                            $filterOrganizator = (  (json_decode($filters->organizator)) != "null") ? json_decode($filters->organizator) : null;
                            $filterTenderType  = ( isset(json_decode($filters->status)->value) ) ? json_decode($filters->status)->value : null;
                            $isElectronic = (isset(json_decode($filters->isElectronic)->value)) ? (json_decode($filters->isElectronic)->value) ? 'ELECTRONIC' : 'PAPER' : null; 
                            $guaranteed =  ( isset(json_decode($filters->guaranteed)->value) ) ? (json_decode($filters->guaranteed)->value) ? "1" : "0" : null;
                            if($filerKind === "private"){
                                $email = true;
                            }

                            if(!is_null($filterProcedure)){
                                foreach($filterProcedure as $fp){
                                    if($request->procedure == $fp->id){
                                        $email = true;  
                                    }
                                }
                            }
                            if(!is_null($filterRegions)){
                                foreach($filterRegions as $fr){
                                    if($regions == $fr->id){
                                        $email = true;  
                                    }
                                }
                            }

                            if($isElectronic === $type){
                                $email = true;  
                            }
                            if( $guaranteed === $request->guaranteed ){
                                $email = true; 
                            }
                            if($filterTenderType == "active" && strtotime($request->end_date) > strtotime( date("Y-m-d H:i:s") )){
                                $email = true;
                            }
                            if($filterTenderType == "finished" && strtotime($request->end_date) < strtotime( date("Y-m-d H:i:s") )){
                                $email = true;
                            }
                            if($filterTenderType == "all"){
                                $email = true;
                            }

                            if($email){
                                UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                                if($user['email_notifications']){
                                    if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                                        ProcessNewTenderAdded::dispatch($data);
                                        $this->sendTenderNotification($user['id'], $tenderState->id, $data->subject, $tenderState->customer_name, 'tender_edited', $request, $ogName, $password, $url);
                                    }
                                }
                            }
                        }
        }
        return redirect('admin/manager/tenders');
    }
    
    public function adminDeleteManagerTender($id){
        TenderState::where("id",$id)->delete();
        TenderStateCpv::where("tender_state_id",$id)->delete();
        return redirect('admin/manager/tenders');

    }
}