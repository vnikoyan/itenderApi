<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender\TenderState;
use App\Models\User\User;
use App\Models\Tender\Organizator;
use App\Models\User\Organisation;
use App\Models\Settings\UserFilters;
use App\Models\Tender\TenderStateCpv;
use App\Jobs\ProcessNewTenderAdded;
use Illuminate\Support\Facades\Log;

class UsersDailyTendersNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usersDailyEmail:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send users daily tenders notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userEmails  = array();

        $tenders = TenderState::whereDate("tender_state.created_at",date("Y-m-d"))->orWhereDate("tender_state.created_at",date("Y-m-d",strtotime("- 1 day")))->get();
        
        foreach ($tenders as $tender) {
            $yesterday = date("Y-m-d",strtotime("- 1 day"))." 10:00:00";
            $today = date("Y-m-d")." 10:00:00";
            if(strtotime($tender->created_at) >= strtotime($yesterday) && strtotime($tender->created_at) <= strtotime($today)){
                $cpvs = json_decode($tender->cpv);

                $cpvUsers = User::select("users.email","users.id")
                        ->join("user_cpvs","user_cpvs.user_id","=","users.id")
                        ->whereIn("user_cpvs.cpv_id",$cpvs)
                        ->join("order","order.user_id","=","users.id")
                        ->where("users.email_notifications",0)
                        ->where("users.email_notifications_time","10:00")
                        ->where("order.type","ACTIVE")
                        ->whereDate("order.end_date",">=",date("Y-m-d"))
                        ->groupBy("users.email")
                        ->get();

                $users = array();
                foreach($cpvUsers as $val){
                    $users[$val->email]['email'] = $val->email;
                    $users[$val->email]['id'] = $val->id;
                }
                $users =  array_values($users);
                $data = new \stdClass();
                switch ($tender->typeName) {
                    case "KMH":
                        $tender_type_name = "Կնքված պայմանագրի մասին հայտարարություն";
                        break;
                    case "PKMH":
                        $tender_type_name = "Պայմանագիր կնքելու մասին հայտարարություն";
                        break;
                    case "CHGYMH":
                        $tender_type_name = "Չկայացած գնման ընթացակարգի մասին հայտարարություն";
                        break;
                    case "HVTPMH":
                        $tender_type_name = "Հրավերի վերաբերյալ տրամադրված պարզաբանումների մասին հայտարարություն";
                        break;
                    case "HKP":
                        $tender_type_name = "Հրավերում կատարված փոփոխություններ";
                        break;
                    default:
                        $tender_type_name = "Նոր տենդեր iTender համակարգում";
                        break;
                }
                $data->subject = $tender_type_name;

                foreach($users as $user){
                    $url = \Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tender->id;
                    $data->email = trim($user['email']);
                    $ogName = $tender->organizator->name;
                    $password = ($tender->password == null) ? 'առանց ծածկագրի' : $tender->password;
                    if($password != null && $data->subject === 'Նոր տենդեր iTender համակարգում'){
                        $data->subject = "Նոր տենդեր iTender համակարգում: Ծածկագիրը՝ $password";
                    }
                    $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                                    Գնման առարկան` ".htmlentities($tender->title)."
                                    </div>
                                    <div style='display: none; max-height: 0px; overflow: hidden;'>
                                    &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                                </div>
                                    <p>Պատվիրատուն՝ ".htmlentities($ogName->name)."</p><br>
                                    <p>Գնման առարկան՝ ".htmlentities($tender->title)."</p></br>
                                    <p>Ծածկագիրը՝ ".$password."</p></br>
                                    <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($tender->start_date))."</p></br>
                                    <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($tender->end_date))."</p>
                                    <a href = '".$url."'>Տեսնել</a></br>
                                    <p>Հարգանքով՝ iTender թիմ</p>";
                    
                    $filters = UserFilters::where("user_id",$user['id'])->first();

                    if(!$filters) {
                        ProcessNewTenderAdded::dispatch($data);
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
                            if($filerKind == $tender->kind){
                                $email++;
                            }
                        }
        
                        if(!is_null($filterProcedure)){
                            foreach($filterProcedure as $fp){
                                if($tender->procedure->name == $fp->id){
                                    $email++;  
                                }
                            }
                        }
        
                        if(!is_null($filterRegions)){
                            foreach($filterRegions as $fr){
                                if($tender->regions == $fr->id){
                                    $email++;
                                }
                            }
                        }
        
                        if(!is_null($filterOrganizator)){
                            foreach($filterOrganizator as $fo){
                                if($fo->id == $tender->organizator){
                                    $email++;  
                                }
                            }
                        }
        
                        if($isElectronic == $tender->type){
                            $email++;  
                        }
        
                        if( $guaranteed == $tender->guaranteed ){
                            $email++; 
                        }
        
                        if($filterTenderType == "active" && strtotime($tender->end_date) > strtotime( date("Y-m-d H:i:s") )){
                            $email++;
                        }
        
                        if($filterTenderType == "finished" && strtotime($tender->end_date) < strtotime( date("Y-m-d H:i:s") )){
                            $email++;
                        }
        
                        if($filterTenderType == "all"){
                            $email = $filterCount;
                        }

                        if($email >= $filterCount){
                            ProcessNewTenderAdded::dispatch($data);
                        }
                    }
                }
            }
        }
    }
}
