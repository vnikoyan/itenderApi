<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Tender\ParserState;
use Illuminate\Support\Facades\Log;

class GetProcurementAnnouncementsRequest3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url, $i, $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $i, $type)
    {
        $this->url = $url;
        $this->i = $i;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pageURL = $this->url."/".$this->i;
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
        $jobList_time = $xpath->query("//div[@class='tender']/div/p");
        $jobList_more = $xpath->query("//div[@class='tender_title']/p/a");
        $index_title = 0;
        foreach ($jobList_time as $key => $item) {
            if($key%2 == 1){
                $jobList_mor = $jobList_more[$index_title];
                $fileURL = trim($jobList_mor->attributes[1]->nodeValue);
                $checkTender = ParserState::where("link",$fileURL)->where("type_name",$this->type)->first();
                if(is_null($checkTender)){
                    $start_date = $jobList_time[$key]->nodeValue;
                    $date = explode(" ", $start_date);
                    $tm = explode(":",$date[3])[0];
                    $todayDate = strtotime($date[2]);
                    $name = $jobList_mor->nodeValue;
                //    if($todayDate == $now){
                        if(!empty($fileURL)){
                            $competition = 0;
                            $start_date = $date[2];
                            $start_time = $date[3];
                            $start_time = explode("-ից",$start_time)[0];
                            $end_date = ( !empty($date[5]) ) ? $date[5] : 0 ; 
                            $end_time = ( !empty($date[6]) ) ? $date[6] : 0 ;
                            $end_date = ( $end_date != 0 ) ? $end_date." ".$end_time : date('Y-m-d H:i:s', strtotime('+1 years')); 
                            $parserState = new ParserState;
                            $parserState->title = $name;
                            $parserState->link = $fileURL;
                            $parserState->start_date = $start_date." ".$start_time;
                            $parserState->end_date = $end_date;
                            $parserState->type_name = $this->type;
                            $parserState->is_competition = $competition;
                            $parserState->save();
                            Log::channel('jobs')->info('TITLE ---'.$name);
                        }
                        $index_title++;
                //    }
                }
            }
        }
    }
}
