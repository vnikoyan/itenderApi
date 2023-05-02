<?php

namespace App\Console\Commands;

use App\Jobs\FixProcurementAnnouncementsEndDates;
use App\Jobs\GetProcurementAnnouncementsRequest1;
use App\Jobs\GetProcurementAnnouncementsRequest2;
use App\Jobs\GetProcurementAnnouncementsRequest3;
use App\Jobs\GetProcurementAnnouncementsRequest4;
use App\Jobs\GetProcurementAnnouncementsRequest5;
use App\Jobs\GetProcurementAnnouncementsRequest6;
use Illuminate\Console\Command;
use App\Models\Tender\ParserState;
use DateTime;
use Illuminate\Support\Facades\Log;

class ProcurementAnnouncementsSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ProcurementAnnouncements:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Procurement Announcements from gnumner.am';

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
        $now = strtotime(date("Y-m-d"));
        $todayDate = date("Y-m-d");
        $count_paging = 10;
        $array_links = array(
            "EMNH" =>"https://gnumner.minfin.am/hy/page/erkpul_mrcuyti_nakhaorakavorman_haytararutyun/",
            "BMNH" =>"https://gnumner.minfin.am/hy/page/bac_mrcuyti_nakhaorakavorman_haytararutyun/",
            "GHNH" =>"https://gnumner.minfin.am/hy/page/gnanshman_harcman_nakhaorakavorman_haytararutyun/",
            "PNMNH" =>"https://gnumner.minfin.am/hy/page/_pak_npatakayin_mrcuyti_nakhaorakavorman_haytararutyun/",
            "PPMNH" =>"https://gnumner.minfin.am/hy/page/pak_parberakan_mrcuyti_nakhaorakavorman_haytararutyun_ev_hraver/", 
            "MAGKH" =>"https://gnumner.minfin.am/hy/page/_mek_andzic_gnumneri_katarman_haytararutyun_ev_hraver/",
            "OPKKG" =>"https://gnumner.minfin.am/hy/page/otarerkrya_petutyunneri_koghmic_kazmakerpvogh_gnumner/",
            "MAEH" =>"https://gnumner.minfin.am/hy/page/mayr_ator_s_ejmiatsni_haytararutyunner/",
            "BMHH" =>"https://gnumner.minfin.am/hy/page/bac_mrcuyti_haytararutyun_ev_hraver/",
            "GHHH" =>"https://gnumner.minfin.am/hy/page/gnanshman_harcman_haytararutyun_ev_hraver/",
            "TIGKKGMH" =>"https://gnumner.minfin.am/hy/page/tsragri_irakanacman_grasenyakneri_koghmic_katarvogh_gnumneri_masin_haytararutyunner_ev_hraverner/",
        );

        foreach($array_links as $type => $url){
            Log::channel('jobs')->info('------------------------------------------------------------------');
            Log::channel('jobs')->info('TYPE ---'.$type);
            for($i = 1; $i <= $count_paging; $i++){
                GetProcurementAnnouncementsRequest1::dispatch($url, $i, $type) ->delay(now()->addSeconds(10));
            }
            Log::channel('jobs')->info('------------------------------------------------------------------');
        }

        $array_links = array(
            "HKKKGVH" => "https://gnumner.minfin.am/hy/page/hanrayin_kazmakerputyunneri_koghmic_katarvogh_gnumneri_veraberyal_haytararutyunner/",
            "HKP" => "https://gnumner.minfin.am/hy/page/hraverum_katarvats_popokhutyunner/",
            "KMH" => "https://gnumner.minfin.am/hy/page/knqvats_paymanagri_masin_haytararutyun/",
            "PKMH" =>"https://gnumner.minfin.am/hy/page/paymanagir_knqelu_masin_haytararutyun/",
            "CHGYMH" =>"https://gnumner.minfin.am/hy/page/chkayacats_gnman_yntacakargi_masin_haytararutyunner/"
        );

        foreach($array_links as $type => $url){
            Log::channel('jobs')->info('------------------------------------------------------------------');
            Log::channel('jobs')->info('TYPE ---'.$type);
            for($i = 1; $i <= $count_paging; $i++){
                GetProcurementAnnouncementsRequest2::dispatch($url, $i, $type) ->delay(now()->addSeconds(10));
            }
            Log::channel('jobs')->info('------------------------------------------------------------------');
        }

        $array_links = array(
            "HVTPMH" =>"https://gnumner.minfin.am/hy/page/hraveri_veraberyal_tramadrvats_parzabanumneri_masin_haytararutyun_1/"
        );
        foreach($array_links as $type => $url){
            Log::channel('jobs')->info('------------------------------------------------------------------');
            Log::channel('jobs')->info('TYPE ---'.$type);
            for($i = 1; $i <= $count_paging; $i++){
                GetProcurementAnnouncementsRequest3::dispatch($url, $i, $type) ->delay(now()->addSeconds(10));
            }
            Log::channel('jobs')->info('------------------------------------------------------------------');
        }


        $array_links = array(
            "HBNA" =>"https://gnumner.minfin.am/hy/page/hayteri_bacman_nisti_ardzanagrutyunner/",
        );

        $count_paging = 30;
        foreach($array_links as $type => $url){
            Log::channel('jobs')->info('------------------------------------------------------------------');
            Log::channel('jobs')->info('TYPE ---'.$type);
            for($i = 1; $i <= $count_paging; $i++){
                GetProcurementAnnouncementsRequest4::dispatch($url, $i, $type) ->delay(now()->addSeconds(10));
            }
            Log::channel('jobs')->info('------------------------------------------------------------------');
        }

        $array_links = array(
            ["HGNA", "https://gnumner.minfin.am/hy/page/hayteri_gnahatman_nisti_ardzanagrutyunner/"],
            ["HBNA", "https://gnumner.minfin.am/hy/page/arevtrayin_ev_och_arevtrayin_kazmakerputyunner/"],
            ["HBNA", "https://gnumner.minfin.am/hy/page/teghakan_inqnakaravarman_marminner/"],
        );

        $count_paging = 30;
        foreach($array_links as $data){
            $type = $data[0];
            $url = $data[1];
            Log::channel('jobs')->info('------------------------------------------------------------------');
            Log::channel('jobs')->info('TYPE ---'.$type);
            for($i = 1; $i <= $count_paging; $i++){
                GetProcurementAnnouncementsRequest5::dispatch($url, $i, $type) ->delay(now()->addSeconds(10));
            }
            Log::channel('jobs')->info('------------------------------------------------------------------');
        }

        $array_links = array(
            ["HGNA", "https://gnumner.minfin.am/hy/page/himnadramner_1/", 1],
            ["HGNA", "https://gnumner.minfin.am/hy/page/petakan_karavarman_marminner_2/", 10],
            // ["HGNA", "https://gnumner.minfin.am/hy/page/hanrayin_kazmakerputyunner_2/", 0],
            ["HGNA", "https://gnumner.minfin.am/hy/page/arevtrayin_ev_och_arevtrayin_kazmakerputyunner_1/", 10],
            ["HGNA", "https://gnumner.minfin.am/hy/page/teghakan_inqnakaravarman_marminner_1/", 3],

            // "HBNA" =>["https://gnumner.minfin.am/hy/page/hanrayin_kazmakerputyunner_1/", 0],
            ["HBNA", "https://gnumner.minfin.am/hy/page/himnadramner_11/", 1],
            ["HBNA", "https://gnumner.minfin.am/hy/page/petakan_karavarman_marminner_1/", 10],
        );

        foreach($array_links as $data){
            $type = $data[0];
            $url = $data[1];
            $count_paging = $data[2];
            Log::channel('jobs')->info('TYPE ---'.$type);
            for($i = 1; $i <= $count_paging; $i++){
                GetProcurementAnnouncementsRequest6::dispatch($url, $i, $type) ->delay(now()->addSeconds(10));
            }
            Log::channel('jobs')->info('------------------------------------------------------------------');
        }
        FixProcurementAnnouncementsEndDates::dispatch();
    }
}
