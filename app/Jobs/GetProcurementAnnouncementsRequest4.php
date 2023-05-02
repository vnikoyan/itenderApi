<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Tender\ParserState;
use Illuminate\Support\Facades\Log;
use DateTime;

class GetProcurementAnnouncementsRequest4 implements ShouldQueue
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
        curl_setopt($ch, CURLOPT_URL, $pageURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $page = curl_exec($ch);
        curl_close($ch);
        @$doc = new \DOMDocument();
        @$doc->loadHTML($page);
        $xpath = new \DomXPath($doc);
        $jobList_time = $xpath->query("//p[@class='tender_time']");
        $jobList_more = $xpath->query("//div[@class='tender_title']/p/a");
        foreach ($jobList_time as $key => $item) {
            $fileURL = trim($jobList_more[$key]->getAttribute('href'));
            $checkTender = ParserState::where("link",$fileURL)->where("type_name",$this->type)->first();
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
                $start_date_obj = DateTime::createFromFormat("Y-m-d", $start_date);
                $start_year = $start_date_obj->format("Y");
                if($start_year == 2022){
                    $name = $jobList_more[$key]->nodeValue;
                    // if( strtotime( $startDate ) ==  $now ){
                        $competition = 0;
                        $parserState = new ParserState;
                        $parserState->title = $name;
                        $parserState->link = $fileURL;
                        $parserState->start_date = $start_date." ".$start_time;
                        $parserState->end_date = $end_date;
                        $parserState->type_name = $this->type;
                        $parserState->is_competition = $competition;
                        $parserState->save();
                        Log::channel('jobs')->info('TITLE ---'.$name);
                    // }
                }
            }
        }
    }
}
