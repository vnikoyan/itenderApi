<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender\FavoriteTenderState;
use App\Models\Tender\TenderStateCpv;
use App\Jobs\ProcessFavoriteTendersEmail;
class FavoriteTendersSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FavoriteTeners:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will check and update aboute favorite tenders end date ';

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
        $nowDate = date("Y-m-d H:i:s");
        $url = \Config::get('values')['frontend_url'];
        $date = date('Y-m-d H', strtotime($nowDate. ' +1 days')).':00';
        $data = new \stdClass();
        $userFavoriteTenders = array();
        $favoriteTenders = FavoriteTenderState::select("tender_state.id","organizator.name as ogName", "tender_state.password", "tender_state.start_date","tender_state.end_date","users.email","tender_state.title","users.id as userId")->join('tender_state','tender_state.id','=','favorite_tender_states.tender_state_id')->join('users','users.id', '=', 'favorite_tender_states.user_id')->join("organizator","organizator.id","=","tender_state.organizer_id")->where("tender_state.end_date", ">", $nowDate)->get();
        foreach($favoriteTenders as $val){
            $userFavoriteTenders[$val->email.'|'.$val->id]['tenderId'] = $val->id;
            $userFavoriteTenders[$val->email.'|'.$val->id]['userId'] = $val->userId;
            $userFavoriteTenders[$val->email.'|'.$val->id]['email'] = $val->email;
        }

        $userFavoriteTenders = array_values($userFavoriteTenders);
        foreach($userFavoriteTenders as $val){
            $tender =   TenderStateCpv::select("tender_state.*","organizator.name as ogName")
                                      ->join("tender_state","tender_state.id","tender_state_cpv.tender_state_id")
                                      ->join("organizator","organizator.id","tender_state.organizer_id")
                                      ->where("tender_state.id",$val['tenderId'])
                                      ->first();
            $endDate = date("Y-m-d H",strtotime($tender->end_date)).':00';
            if(strtotime($endDate) == strtotime($date) ){
                $data->email = trim($val['email']);
                $name = json_decode($tender->title);
                $tender->password = trim($tender->password);
                $password = ( strlen($tender->password) == 0 ) ? 'առանց ծածկագրի' : $tender->password;
                $data->subject = "Հիշեցում տենդերի վերջնաժամկետի վերաբերյալ";
                $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                                Գնման առարկան` ".htmlentities($name->hy)."
                                </div>
                                <div style='display: none; max-height: 0px; overflow: hidden;'>
                                &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                               </div>
                               <p>Հարգելի գործընկեր,</p></br>
                               <p> Այս տենդերի վերջնաժամկետը լրանում է".date("Y-m-d H:i", strtotime($tender->end_date))."</p></br>
                                <p>Պատվիրատուն՝ ".htmlentities($tender->ogName)."</p><br>
                                <p>Գնման առարկան՝ ".htmlentities($name->hy)."</p></br>
                                <p>Ծածկագիրը՝ ".$password."</p></br>
                                <p>Սկիզբ՝ ".date("Y-m-d",strtotime($tender->start_date))."</p></br>
                                <p>Հարգանքով՝ iTender թիմ</br>";
                               // <a href = '".$url = \Config::get('values')['frontend_url']."/participant/tenders?id=".$tender->id."'>Տեսնել</a></br>
                ProcessFavoriteTendersEmail::dispatch($data);
            }
        }
    }
}
