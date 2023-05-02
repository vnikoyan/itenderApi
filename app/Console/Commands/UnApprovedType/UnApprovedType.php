<?php

namespace App\Console\Commands\UnApprovedType;

use Illuminate\Console\Command;

use Goutte\Client;
use Storage;
use DB;

class UnApprovedType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
    */
    protected $signature = 'unapproved:typeAll';

    protected $url = "http://gnumner.am/";
    protected $urlPhats = [ ["phat_hy" => "/hy/page/elektronayin_achurdi_haytararutyun_ev_hraver/", "phat_ru" => "/ru/page/obyavlenie_i_priglashenie_na_elektronnyi_auktcion/", "phat_en" => "/en/page/announcement_and_invitation_of_an_electronic_auction/", "type" => "1"], ["phat_hy" => "/hy/page/erkpul_mrcuyti_nakhaorakavorman_haytararutyun/", "phat_ru" => "/ru/page/predkvalifikatcionnoe_obyavlenie_na_dvukhetapnyi_otkrytyi_konkurs/", "phat_en" => "/en/page/prequalification_announcement_of_the_two-stage_open_tender/", "type" => "2", ], ["phat_hy" => "/hy/page/bac_mrcuyti_nakhaorakavorman_haytararutyun/", "phat_ru" => "/ru/page/predkvalifikatcionnoe_obyavlenie_na_otkrytyi_konkurs/", "phat_en" => "/en/page/open_tender_prequalification_announcement_/", "type" => "3", ], ["phat_hy" => "/hy/page/gnanshman_harcman_nakhaorakavorman_haytararutyun/", "phat_ru" => "/ru/page/zapros_tcenovykh_predlozhenii/", "phat_en" => "/en/page/quotation_request/", "type" => "4", ], ["phat_hy" => "/hy/page/_pak_npatakayin_mrcuyti_nakhaorakavorman_haytararutyun/", "phat_ru" => "/ru/page/predkvalifikatcionnoe_obyavlenie_na_zakrytyi_tcelevoi_konkurs/", "phat_en" => "/en/page/prequalification_announcement_of_the_target_closed_tender/", "type"    => "5", ], ["phat_hy" => "/hy/page/pak_parberakan_mrcuyti_nakhaorakavorman_haytararutyun_ev_hraver/", "phat_ru" => "/ru/page/predkvalifikatcionnoe_obyavlenie_i_priglashenie_na_zakrytyi_periodicheskii_konkurs/", "phat_en" => "/en/page/prequalification_announcement_and_invitation_of_the_closed_periodic_tender/", "type" => "6", ], ["phat_hy" => "/hy/page/_mek_andzic_gnumneri_katarman_haytararutyun_ev_hraver/", "phat_ru" => "/ru/page/obyavlenie_i_priglashenie_na_zakupku_iz_odnogo_istochnika/", "phat_en" => "/en/page/announcement_and_invitation_on_single-source_procurement_/", "type" => "7", ], ["phat_hy"  => "/hy/page/knqvats_paymanagri_masin_haytararutyun/", "phat_ru"  => "/ru/page/obyavlenie_o_podpisannom_kontrakte/", "phat_en"  => "/en/page/announcement_of_the_signed_contract/", "type"     => "8", ], ["phat_hy"  => "/hy/page/knqvats_paymanagreri_masin_haytararutyunner/", "phat_ru"  => "/ru/page/obyavleniya_o_podpisannykh_kontraktakh/", "phat_en"  => "/en/page/_announcements_on_signed_contracts/", "type"     => "81", ], ["phat_hy"  => "/hy/page/hraverum_katarvats_popokhutyunner/", "phat_ru"  => "/ru/page/izmeneniya_vnesennye_v_priglashenie/", "phat_en"  => "/en/page/changes_to_the_invitation/", "type"     => "9", ], ["phat_hy"  => "/hy/page/otarerkrya_petutyunneri_koghmic_kazmakerpvogh_gnumner/", "phat_ru"  => "/ru/page/zakupki_provodimye_v_zarubezhnykh_stranakh/", "phat_en"  => "/en/page/procurements_organized_fy_foreign_states/", "type"     => "10", ], ["phat_hy"   => "/hy/page/mayr_ator_s_ejmiatsni_haytararutyunner/", "phat_ru"   => "/ru/page/obyavleniya_pervoprestolnyi_echmiadzina/", "phat_en"   => "/en/page/announcements_of_mother_see_of_holy_etchmiadzin/", "type"     => "11", ], ["phat_hy"  => "/hy/page/hravernerum_katarvats_popokhutyunner_1/", "phat_ru"  => "/ru/page/izmeneniya_vnesennye_v_priglashenie_1/", "phat_en"  => "/en/page/hravernerum_katarvats_popokhutyunner_1/", "type"     => "12", ], ["phat_hy"  => "/hy/page/gnman_gortsyntacneri_kazmakerpman_masin_haytararutyunner/", "phat_ru"  => "/ru/page/obyavleniya_ob_organizatcii_zakupochnykh_protcedur/", "phat_en"  => "/en/page/announcements_on_organizing_procurement_procedure/", "type"     => "121", ], ["phat_hy"  => "/hy/page/gnahatogh_handznazhoghovi_nisteri_ardzanagrutyunner/", "phat_ru"  => "/ru/page/gnahatogh_handznajoghov/", "phat_en"  => "/en/page/gnahatogh_handznajoghov/", "type"     => "122", ], ["phat_hy"  => "/hy/page/gnahatogh_handznazhoghovi_nisteri_ardzanagrutyunner/", "phat_ru"  => "/ru/page/obyavleniya_o_podpisannykh_kontraktakh_1/", "phat_en"  => "/en/page/_announcements_of_signing_contracts_1/", "type"     => "123", ], ["phat_hy"  => "/hy/page/chkayacats_yntacakargi_haytararutyunner/", "phat_ru"  => "/ru/page/obyavlenie_o_nesostoyavsheisya_protcedure_zakupki/", "phat_en"  => "/en/page/chkajacac_haytararutyunner/", "type"     => "124", ], ["phat_hy"  => "/hy/page/bac_mrcuyti_haytararutyun_ev_hraver/", "phat_ru"  => "/ru/page/obyavlenie_i_priglashenie_na_otkrytyi_konkurs/", "phat_en"  => "/en/page/announcement_and_invitation_of_an_open_tender/", "type"     => "13", ], ["phat_hy"  => "/hy/page/gnanshman_harcman_haytararutyun_ev_hraver/", "phat_ru"  => "/ru/page/obyavlenie_i_priglashenie_po_zaprosu_kotirovok/", "phat_en"  => "/en/page/announcement_and_invitation_at_the_request_of_quotation/", "type"     => "14", ] ];

    protected $phat_hy = "";
    protected $phat_ru = "";
    protected $phat_en = "";
    protected $type = "";

    protected $client = "";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Էլեկտրոնային աճուրդի հայտարարություն և հրավեր ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->urlPhats as $key =>$value){
            // if($key > 8){
                echo "\n".$key;
                $this->phat_hy = $value["phat_hy"];
                $this->phat_ru = $value["phat_ru"];
                $this->phat_en = $value["phat_en"];
                $this->type    = $value["type"];
                $this->parsing();
            // }
            // $value = $this->urlPhats[3];
            // echo "\n ".$value["phat_hy"]."\n";

        }
    }

    protected function parsing()
    {                          
        $now = \strtotime( date('Y-m-d') );
        $now2 = \strtotime( date('Y-m-d') );
        $url = $this->url.$this->phat_hy;
        $crawler = $this->client->request('GET', $url);

        $jobList_time = $crawler->filter(".tender > div:last-child > p ");


        $hayt_time = $jobList_time->eq(0)->text();
        $hayt_time_array = explode(" ", $hayt_time);
        $time = strtotime($hayt_time_array[2]);

        $start_time = explode("-ից", $hayt_time_array[3]);
        $hayt_start = $hayt_time_array[2]." ".$start_time[0];
      

        if(\strtotime($hayt_time_array[2]) < $now2){ 
            return true;
        }

        $page  = $crawler->filter('.pagination  a:nth-last-child(2)'); 
        $page_count = 1;
        if ($page->count() > 0 ) {
            $page_count  = $page->text();
        }
        $data = [];
    
        for ($i=1; $i <= $page_count ; $i++) { 
          $url = $this->url.$this->phat_hy.$i;

          $crawler = $this->client->request('GET', $url);

          $jobList_time = $crawler->filter(".tender div:last-child > p");
          $jobList_more = $crawler->filter("div.tender_title p a");


          $hayt_time = $jobList_time->eq(0)->text();
          $hayt_time_array = explode(" ", $hayt_time);
          $time = strtotime($hayt_time_array[2]);
  
          $start_time = explode("-ից", $hayt_time_array[3]);
          $hayt_start = $hayt_time_array[2]." ".$start_time[0];

          if(strpos($hayt_time_array[4], 'անժամկետ)') !== false){
                $hayt_end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($hayt_time_array[2])) . "+1 years"));
            }else{
                $hayt_end = $hayt_time_array[5]." ".$hayt_time_array[6];
            }
            
            
            if(\strtotime($hayt_time_array[2]) < $now2 ){
                 break; 
            }

          $index = 0;
          $data[] = $jobList_time->each(function ($node) use ($now,&$jobList_more,&$index,&$i) {
                $hayt_time = $node->text();
                $hayt_time_array = explode(" ", $hayt_time);
                $time = strtotime($hayt_time_array[2]);
             
                // if($time == $now){
                    $jobList_node = $jobList_more->eq($index);

                    $hayt_file = $jobList_node->attr('href');
                    $hayt_title = $jobList_node->text();

                    $start_time = explode("-ից", $hayt_time_array[3]);
                    $hayt_start = $hayt_time_array[2]." ".$start_time[0];
                    if(strpos($hayt_time_array[4], 'անժամկետ)') !== false){
                        $hayt_end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($hayt_time_array[2])) . "+1 years"));
                    }else{
                        $hayt_end = $hayt_time_array[5]." ".$hayt_time_array[6];
                    }
                    $now2 = \strtotime( date('Y-m-d') );

                    if(\strtotime($hayt_end) > $now2){
                        // $url = $this->url.$this->phat_ru.$i;
                        // $crawler_ru = $this->client->request('GET', $url);
                        // $jobList_more_ru = $crawler_ru->filter("div.tender_title p a")->eq($index);

                        // $hayt_file_ru = $jobList_more_ru->attr('href');
                        // $hayt_title_ru = $jobList_more_ru->text();

                        // $url = $this->url.$this->phat_en.$i;
                        // $crawler_en = $this->client->request('GET', $url);
                        // $jobList_more_en = $crawler_en->filter("div.tender_title p a")->eq($index);

                        // $hayt_file_en = $jobList_more_en->attr('href');
                        // $hayt_title_en = $jobList_more_en->text();

                        // $link = [
                        //     "hy" => $hayt_file,
                        //     "ru" => $hayt_file_ru,
                        //     "en" => $hayt_file_en
                        // ];
                        // $title = [
                        //     "hy" => $hayt_title,
                        //     "ru" => $hayt_title_ru,
                        //     "en" => $hayt_title_en
                        // ];


                        $link = [
                            "hy" => $hayt_file,
                            "ru" => $hayt_file,
                            "en" => $hayt_file
                        ];
                        $title = [
                            "hy" => $hayt_title,
                            "ru" => $hayt_title,
                            "en" => $hayt_title
                        ];
                        $index++;
                        return [
                            "start_date" =>  $hayt_start,
                            "end_date"   =>  $hayt_end,
                            "link"       =>  json_encode($link,JSON_UNESCAPED_UNICODE),
                            "title"      =>  json_encode($title,JSON_UNESCAPED_UNICODE),
                            "type"       =>  $this->type,
                        ];
                    }else{
                        return [];
                    }
           }); 
        }

        $data_ = [];
        foreach($data as $key => $value){
            foreach($value as $k => $v){
                if(!empty($v)){
                    $data_[] = $v;
                }
            }
        }
        DB::table('tender_state_parser')->insertOrIgnore($data_);


        return true;
    }
}
