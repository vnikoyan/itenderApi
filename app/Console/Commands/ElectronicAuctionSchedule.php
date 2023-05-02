<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender\ParserState;
use Illuminate\Support\Facades\DB;
class ElectronicAuctionSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'electronicAuctionSchedule:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            "ELAH" => "https://gnumner.minfin.am/hy/page/elektronayin_achurdi_haytararutyun_ev_hraver/",
        );

        foreach($array_links as $type => $url){
            for($i = 1; $i <= $count_paging; $i++){
                $pageURL = $url."/".$i;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch,CURLOPT_URL, $pageURL);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
                $page = curl_exec($ch);
                curl_close($ch);
                @$doc = new \DOMDocument();
                @$doc->loadHTML($page);
                $xpath = new \DomXPath($doc);
                $jobList_time = $xpath->query("//p[@class='tender_time']");
                $jobList_more = $xpath->query("//div[@class='tender_title']/p/a");
                foreach ($jobList_time as $key => $item) {
                    $fileURL = $jobList_more[$key]->getAttribute('href');
                    $checkTender = ParserState::where("link","LIKE",'%'.$fileURL.'%')->first();
                    if(is_null($checkTender)){
                        $date = explode(" ", $item->nodeValue);
                        $start_date = $date[2];
                        $start_time = $date[3];
                        $tm = explode(":",$start_time)[0];
                        $startDate = $start_date;
                        $start_time = explode("-ից",$start_time)[0]; 
                        $end_date = ( !empty($date[5]) ) ? $date[5] : 0 ; 
                        $end_time = ( !empty($date[6]) ) ? $date[6] : 0 ;
                        $end_date = ( $end_date != 0 ) ? $end_date." ".$end_time : date('Y-m-d H:i:s', strtotime('+1 years')); 
                        $name = $jobList_more[$key]->nodeValue;
                        // if( strtotime( $startDate ) ==  $now ){
                            $electronicAuction = $this->getElectronicAuctions($fileURL);
                            $competition = 1;
                            $parserState = new ParserState;
                            $parserState->title = $name;
                            $parserState->link = $fileURL;
                            $parserState->start_date = $start_date." ".$start_time;
                            $parserState->end_date = $end_date;
                            $parserState->type_name = $type;
                            $parserState->is_competition = $competition;
                            $parserState->password = $electronicAuction['password'];
                            $parserState->customer_name = $electronicAuction['custumer_name'];
                            $parserState->save();
                        // }
                    }
                }
            }
        }
    }

    public function getElectronicAuctions($link){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_URL, $link);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $page = file_get_contents($link);
        curl_close($ch);
        @$doc = new \DOMDocument();
        @$doc->loadHTML($page);
        $xpath = new \DomXPath($doc);
        $textData = $xpath->query("//div[@class='de_v']");
        $fileDownloadUrL = $xpath->query("//div[@class='fe_v']");
        if($fileDownloadUrL[5]){
            $ZipURL = $fileDownloadUrL[5]->childNodes[1]->firstChild->parentNode->attributes[0]->textContent;
            $ch = curl_init($ZipURL);
            $dir = public_path('/files/zip/');
            $file_name = basename($ZipURL);
            $save_file_loc = $dir . $file_name;
            
            $fp = fopen($save_file_loc, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
    
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        }

        $data = array();
        $data['password'] =  $textData[0]->textContent;
        $data['custumer_name'] =  $textData[2]->textContent;
        $organizator =  DB::table('organizator')->where("name","LIKE","%".$textData[2]->textContent."%")->first();
        if(empty($organizator)){
            DB::table('organizator')->insert([
                'name' => $textData[2]->textContent,
                'is_state' => 1
            ]);
        }
        return $data;
    }
}
