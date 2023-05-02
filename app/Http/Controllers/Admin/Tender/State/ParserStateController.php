<?php
namespace App\Http\Controllers\Admin\Tender\State;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Tender\TenderStateCpv;
use App\Models\Tender\ParserState;
use App\Models\Tender\TenderStateCategory;
use App\Models\Tender\TenderState;
use App\Models\Tender\TenderStateArchive;
use App\Models\Tender\Procedure;
use App\Models\Tender\Organizator;
use App\Models\User\User;
use App\Models\Tender\FavoriteTenderState;
use App\Models\Settings\UserFilters;
use App\Jobs\ProcessNewTenderAdded;
use App\Models\Translation\Language;
use App\Models\Settings\Ministry;
use App\Models\Settings\Region;
use App\Models\Settings\StateInstitution;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Tender\State\TenderStoreAndUpdateRequest;
use Auth;
use App\Models\Cpv\Cpv;
use App\Models\Settings\Units;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Notifications\SendNotification;
use App\Models\Tender\UserCorrespondingTenders;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class ParserStateController extends AbstractController
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
    public function index(){
        return view('admin.tender.state.parser.allParsers');
    }
    // public function index(){
    //     var_dump('expression');die;
    //     $type = [["g_link" => "http://gnumner.am/hy/page/elektronayin_achurdi_haytararutyun_ev_hraver/", "name"  => "Էլեկտրոնային աճուրդի հայտարարություն և հրավեր", "type" => "1"], ["g_link" => "http://gnumner.am/hy/page/erkpul_mrcuyti_nakhaorakavorman_haytararutyun/", "name"  => "Երկփուլ մրցույթի նախաորակավորման հայտարարություն", "type" => "2"], ["g_link" => "http://gnumner.am/hy/page/bac_mrcuyti_nakhaorakavorman_haytararutyun/", "name"  => "Բաց մրցույթի նախաորակավորման հայտարարություն", "type" => "3"], ["g_link" => "http://gnumner.am/hy/page/gnanshman_harcman_nakhaorakavorman_haytararutyun/", "name"  => "Գնանշման հարցման նախաորակավորման հայտարարություն", "type" => "4"], ["g_link" => "http://gnumner.am/hy/page/_pak_npatakayin_mrcuyti_nakhaorakavorman_haytararutyun/", "name"  => "Փակ նպատակային մրցույթի նախաորակավորման հայտարարություն", "type" => "5"], ["g_link" => "http://gnumner.am/hy/page/pak_parberakan_mrcuyti_nakhaorakavorman_haytararutyun_ev_hraver/", "name"  => "Փակ պարբերական մրցույթի նախաորակավորման հայտարարություն և հրավեր", "type" => "6"], ["g_link" => "http://gnumner.am/hy/page/_mek_andzic_gnumneri_katarman_haytararutyun_ev_hraver/", "name"  => "Մեկ անձից գնումների կատարման հայտարարություն և հրավեր", "type" => "7"], ["g_link" => "http://gnumner.am/hy/page/knqvats_paymanagri_masin_haytararutyun/", "name"  => "Կնքված պայմանագրի մասին հայտարարություն", "type" => "8"], ["g_link" => "http://gnumner.am/hy/page/hraverum_katarvats_popokhutyunner/", "name"  => "Հրավերում կատարված փոփոխություններ", "type" => "9"], ["g_link" => "http://gnumner.am/hy/page/otarerkrya_petutyunneri_koghmic_kazmakerpvogh_gnumner/", "name"  => "Օտարերկրյա պետությունների կողմից կազմակերպվող գնումներ", "type" => "10"], ["g_link" => "http://gnumner.am/hy/page/mayr_ator_s_ejmiatsni_haytararutyunner/", "name"  => "Մայր Աթոռ Ս. Էջմիածնի հայտարարություններ", "type" => "11"], ["g_link" => "http://gnumner.am/hy/page/hanrayin_kazmakerputyunneri_koghmic_katarvogh_gnumneri_veraberyal_haytararutyunner/", "name"  => "Հանրային կազմակերպությունների  կողմից կատարվող գնումների ", "type" => "12"], ["g_link" => "http://gnumner.am/hy/page/bac_mrcuyti_haytararutyun_ev_hraver/", "name"  => "Բաց մրցույթի հայտարարություն և հրավեր", "type" => "13"], ["g_link" => "http://gnumner.am/hy/page/gnanshman_harcman_haytararutyun_ev_hraver/", "name"  => "Գնանշման հարցման հայտարարություն և հրավեր", "type" => "14"]];
        
    //     return view('admin.tender.state.patser.index',compact('type'));
    // }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexType($type){
        return view('admin.tender.state.parser.indexType',compact('type'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(ParserState $parserState){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.tender.state.parser.add',compact('parserState','language'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(TenderStoreAndUpdateRequest $request,Info $info){
    //     foreach($request->title as $key => $value){
    //         $info->setTranslation('title', $key , $value);
    //         $info->setTranslation('description', $key , $request->description[$key]);
    //     }
    //     $info->order         = $request->order;
    //     $info->save();
    //     return redirect("/admin/info");
    // }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $parserState = ParserState::findOrFail($id);
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.tender.state.parser.edit',compact('parserState','language'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(TenderStoreAndUpdateRequest $request,$id,TenderState $tenderState){
        foreach($request->title as $key => $value){
            $tenderState->setTranslation('title', $key , $value);
            // $tenderState->setTranslation('link', $key , $request->link[$key]);
        }
        $cpvCodes = array();
        $organizator = explode(',',$request->organizator);
        $cpv = ($request->cpvOrCategory == "cpv") ? json_encode($request->cpv) : 0;
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
        
        $type = ($request->type == null ) ? 0 : $request->type;
        $procedure = ($request->procedure == null ) ? 0 : $request->procedure;
        $is_competition = ($request->is_competition == null ) ? null : $request->is_competition;
        $passwordTender = ($request->passwordTender == null ) ? " " : $request->passwordTender;
        $is_closed = ($request->is_closed == null) ? 0 : 1 ;
        $tender_state_id = ($request->searchTenderState == null ) ? 0 : $request->searchTenderState;
        $beneficiari = ($request->beneficiari == null ) ? 0 : $request->beneficiari;
        $is_with_model = ($request->is_with_model == null ) ? 0 : $request->is_with_model;
        $tenderState->start_date                = $request->start_date;
        $tenderState->end_date                  = $request->end_date;
        $tenderState->cpv                       = $cpv;
        $tenderState->ministry                  = $request->ministry;
        $tenderState->state_institution         = $request->state_institution;
        $tenderState->regions                   = $regions;
        $tenderState->type                      = $type;
        $tenderState->tender_type               = $organizator[1];
        $tenderState->is_competition            = $is_competition;
        $tenderState->is_new                    = $request->is_new;
        $tenderState->is_closed                 = $is_closed;
        $tenderState->estimated                 = $request->estimated;
        $tenderState->password                  = $passwordTender;
        $tenderState->invitation_link           = $request->inovation_link;
        $tenderState->category                  = $category;
        $tenderState->organizer_id              = $organizator[0];
        $tenderState->kind                      = $kind;
        $tenderState->procedure_type            = $procedure;
        $tenderState->guaranteed                = $request->guaranteed;
        $tenderState->tender_state_id           = $tender_state_id;
        $tenderState->type_name                 = $request->type_name;
        $tenderState->beneficiari               = $beneficiari;
        $tenderState->is_with_model               = $is_with_model;


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
            $tenderState->cpv_codes = json_encode($request->cpv);
            $tenderState->save();
        }

        
        if($tender_state_id != 0){
            $cpvs = TenderStateCpv::where("tender_state_id",$tender_state_id)->leftJoin("cpv","cpv.id","=","tender_state_cpv.cpv_id")->get();
            foreach($cpvs as $val){
                $cpv_code = (is_null($val->cpv_code) || empty($val->cpv_code)) ? $val->code : $val->cpv_code ;
                $tenderStateCpv = new TenderStateCpv();
                $tenderStateCpv->cpv_id = $val->cpv_id;
                $tenderStateCpv->tender_state_id = $tenderState->id;
                $tenderStateCpv->view_id = $val->view_id;
                $tenderStateCpv->cpv_name = $val->cpv_name;
                $tenderStateCpv->cpv_code = $cpv_code;
                if( (int) round($val->estimated_price) == 0 ){
                    $tenderStateCpv->is_condition = 1;
                }
                $tenderStateCpv->estimated_price = $val->estimated_price;
                $tenderStateCpv->save();
            }

            $data =  tenderState::select("cpv","estimated_file","invitation_link","cpv_codes")->where("id",$tender_state_id)->first();
            if(!$data){
                $data =  TenderStateArchive::select("cpv","estimated_file","invitation_link","cpv_codes")->where("id",$tender_state_id)->first();
            }
            $cpv = $data->cpv;
            $tenderState->cpv = $data->cpv;
            $tenderState->cpv_codes = $data->cpv_codes;
            $favTenders = false;
            $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $passwordTender;
            if($request->passwordTender != null){
                $tender_type_name = "Նոր տենդեր iTender համակարգում: Ծածկագիրը՝ $password";
            } else {
                $tender_type_name = "Նոր տենդեր iTender համակարգում";
            }
            switch ($request->type_name) {
                case "KMH":
                    $favTenders = true;
                    $tender_type_name = "Կնքված պայմանագրի մասին հայտարարություն";
                    break;
                case "PKMH":
                    $favTenders = true;
                    $tender_type_name = "Պայմանագիր կնքելու մասին հայտարարություն";
                    break;
                case "CHGYMH":
                    $tender_type_name = "Չկայացած գնման ընթացակարգի մասին հայտարարություն";
                    $favTenders = true;
                    break;
                case "HVTPMH":
                    $tender_type_name = "Հրավերի վերաբերյալ տրամադրված պարզաբանումների մասին հայտարարություն";
                    $favTenders = true;
                    break;
                case "HKP":
                    $tender_type_name = "Հրավերում կատարված փոփոխություններ";
                    $favTenders = true;
                    break;
            }
            if($favTenders){
                $usersFavoritTender = FavoriteTenderState::select("users.id","users.email","users.email_notifications","users.telegram_notifications")
                                                         ->where("tender_state_id",$tender_state_id)
                                                         ->join("users","users.id","favorite_tender_states.user_id")
                                                         ->get();
                foreach($usersFavoritTender as $user){
                    $url = \Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
                    $ogName = Organizator::where("id",$organizator[0])->select("name")->first();
                    $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $passwordTender;
                    $data = new \stdClass();
                    $data->subject = $tender_type_name;
                    $data->email = $user['email'];
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
                    if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                        UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                        if($user['email_notifications']){
                            ProcessNewTenderAdded::dispatch($data);
                        }
                        if($user['telegram_notifications']){
                            $subject = $tender_type_name;

                            $content = "*$subject*
            
Պատվիրատուն՝ *".$ogName->name."*
Գնման առարկան՝ *".$request->title['hy']."*
Ծածկագիրը՝ *".$password."*
Սկիզբ՝ *".date("Y-m-d H:i",strtotime($request->start_date))."*
Վերջնաժամկետը՝ *".date("Y-m-d H:i",strtotime($request->end_date))."*
                                        ";
                            $userData = User::find($user['id'])->where('telegram_notifications', 1)->orWhere('telegram_id','<>','');
                            if($userData){
                                Notification::send($userData, new SendNotification($user['id'], $content, $url));
                            }
                        }
                    }
                }
            }

            $tenderState->save();

        }

        if($cpv != "0" && $tender_state_id == 0){
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
                    if( (int) round( $request->input($estimated_price)) == 0 ){
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
                    $tenderStateCpv->save();
                    $cpvCodes[] = $request->input("cpv_code_ne_".$i."");
            }
        }

        $tenderState->cpv_codes = json_encode($request->cpv);
        $tenderState->save();
        
        if($category != "0"){
            foreach($request->cpv as $key => $value){
                $tenderStateCategory = new TenderStateCategory();
                $tenderStateCategory->category_id = $value;
                $tenderStateCategory->tender_state_id = $tenderState->id;
                $tenderStateCategory->save();
            }
        }
        if($request->type_name !== 'KMH' && 
            $request->type_name !== 'PKMH' && 
            $request->type_name !== 'HBNA' && 
            $request->type_name !== 'HGNA' && 
            $request->type_name !== 'CHGYMH'){
            $sendEmails = false;
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
            $cpvUsers = User::select("users.email","users.id","users.email_notifications","users.telegram_notifications")
                            ->join("user_cpvs","user_cpvs.user_id","=","users.id")
                            ->whereIn("user_cpvs.cpv_id",$cpvs)
                            ->join("order","order.user_id","=","users.id")
                            ->where("order.package_id","!=",1)
                            ->where("order.type","ACTIVE")
                            ->whereDate("order.end_date",">=",date("Y-m-d"))
                            ->groupBy("users.email")
                            ->get();
            $searchableTenderUser = TenderStateCpv::select("users.email","users.id","users.email_notifications","users.telegram_notifications")
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
                $users[$val->email]['telegram_notifications'] = $val->telegram_notifications;
            }
            foreach($searchableTenderUser as $val){
                $users[$val->email]['email'] = $val->email;
                $users[$val->email]['id'] = $val->id;
                $users[$val->email]['email_notifications'] = $val->email_notifications;
                $users[$val->email]['telegram_notifications'] = $val->telegram_notifications;
            }
    
            $users =  array_values($users);
            $password = ($request->passwordTender == null) ? 'առանց ծածկագրի' : $passwordTender;
            if( ($is_competition == 1  || $request->type_name == "HVTPMH" || $request->type_name == "HKP")){
                $sendEmails = true;
            }else{
                $sendEmails = true;
            }
            if($sendEmails){
                foreach($users as $user){
                    UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                    $url = \Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
                    $data->email = $user['email'];
                    $ogName = Organizator::where("id",$organizator[0])->select("name")->first();
                    if($request->passwordTender != null){
                        $tender_type_name = "Նոր տենդեր iTender համակարգում: Ծածկագիրը՝ $password";
                    } else {
                        $tender_type_name = "Նոր տենդեր iTender համակարգում";
                    }
                    switch ($request->type_name) {
                        case "HVTPMH":
                            $tender_type_name = "Հրավերի վերաբերյալ տրամադրված պարզաբանումների մասին հայտարարություն";
                            $favTenders = true;
                            break;
                        case "HKP":
                            $tender_type_name = "Հրավերում կատարված փոփոխություններ";
                            $favTenders = true;
                            break;
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
                    $data->subject = $tender_type_name;
                    $filters = UserFilters::where("user_id",$user['id'])->first();
                    $email = false;
                    if(is_null($filters)) {
                        if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                            if($user['email_notifications']){
                                ProcessNewTenderAdded::dispatch($data);
                            }
                            if($user['telegram_notifications']){
                                $subject = $tender_type_name;

                                $content = "*$subject*
                
Պատվիրատուն՝ *".$ogName->name."*
Գնման առարկան՝ *".$request->title['hy']."*
Ծածկագիրը՝ *".$password."*
Սկիզբ՝ *".date("Y-m-d H:i",strtotime($request->start_date))."*
Վերջնաժամկետը՝ *".date("Y-m-d H:i",strtotime($request->end_date))."*
                                            ";
                                $userData = User::find($user['id'])->where('telegram_notifications', 1)->orWhere('telegram_id','<>','');
                                if($userData){
                                    Notification::send($userData, new SendNotification($user['id'], $content, $url));
                                }
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
                            if(!Carbon::parse($request->end_date)->lessThanOrEqualTo(Carbon::now())) {
                                UserCorrespondingTenders::insert(['user_id'=> $user['id'], 'tender_id'=> $tenderState->id]);
                                if($user['email_notifications']){
                                        ProcessNewTenderAdded::dispatch($data);
                                }
                                if($user['telegram_notifications']){
                                    $subject = $tender_type_name;

                                    $content = "*$subject*
                    
Պատվիրատուն՝ *".$ogName->name."*
Գնման առարկան՝ *".$request->title['hy']."*
Ծածկագիրը՝ *".$password."*
Սկիզբ՝ *".date("Y-m-d H:i",strtotime($request->start_date))."*
Վերջնաժամկետը՝ *".date("Y-m-d H:i",strtotime($request->end_date))."*
                                                ";
                                    $userData = User::find($user['id'])->where('telegram_notifications', 1)->orWhere('telegram_id','<>','');
                                    if($userData){
                                        Notification::send($userData, new SendNotification($user['id'], $content, $url));
                                    }
                                }
                            }
                        }
                  }
                }
            }
        }
        $parser = ParserState::where('id', $id)->first();
        ParserState::where('id', $id)->update(['published' => "1"]);

        return redirect($request->previous);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        ParserState::findOrFail($id)->delete();
        return redirect()->back();
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData($type = "all"){


        if($type == "all"){
            $tableData =  Datatables::of(ParserState::select("*")->orderBy('start_date','DESC'));
        }else{
            $tableData =  Datatables::of(ParserState::where("type",$type)->select("*")->orderBy('start_date','DESC'));
        }

        return $tableData->addColumn('action', function ($tender) {
                     return '<a href="'.$tender->link.'" target="_blank" class="btn btn-xs btn-primary" data-toggle="tooltip-custom" data-placement="right" title="Պայմանագիր" data-original-title="Պայմանագիր" data-trigger="hover"><i class="fa fa-file"></i></a>  
                             <a href="/admin/tender_state_parser/'.$tender->id.'/edit" titi class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                             <a href="#" data-tableName="userTable" data-href="/admin/tender_state_parser/delete/'.$tender->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->addColumn('title', function ($tender) {
                    return $tender->title;
                 })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function fileDownload($id){
        return \Storage::download("public/info/".Info::findOrFail($id)->file);
    }

    public function getNotApproved(Request $request){
        if($request->input('parserType') == "all"){
            $data = Datatables::of(ParserState::orderBy('start_date', "DESC")->get());
        }else{
            $data = Datatables::of(ParserState::where("type_name",$request->input('parserType'))->orderBy('start_date', "DESC")->get());
        }
        return $data->addColumn('action', function ($tender) {
            return '<input class="parser_ids" type="checkbox" name=parsers_id[] value='.$tender->id.' />';
        })->editColumn('id', 'ID: {{$id}}')->make(true);
    }

    public function tenderStateParserEdit($id){
        $regions = Region::orderBy('id','ASC')->get();
        $units  = Units::orderBy('id','ASC')->get();
        $parserState = ParserState::where('id',$id)->first();
        $organizator = Organizator::orderBy('id','ASC')->get();
        $procedure = Procedure::orderBy('id','ASC')->get();
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        foreach($language as $val){
            if($val->name == "hy"){
                $val->title = $parserState->title; 
            }
        }
        return view('admin.tender.state.parser.edit',compact('parserState','language','organizator','regions','units','procedure'));

    }

    public function getTenderByType($type){

        return view('admin.tender.state.parser.index',compact('type'));
    }

    public function allTenders(){
        $type = "all";
        return view('admin.tender.state.parser.index',compact('type'));
    }

    public function removeParsersById(Request $request){
        foreach ($request->parserIdS as $id) {
            ParserState::where("id",$id)->delete();
        }

        return 1;
    }

}