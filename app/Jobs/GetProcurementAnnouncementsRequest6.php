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

class GetProcurementAnnouncementsRequest6 implements ShouldQueue
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
        $jobList_time_alt = $xpath->query("//div[@class='tender_title']/parent::div");
        $jobList_more = $xpath->query("//div[@class='tender_title']/p/a");
        foreach ($jobList_time_alt as $key => $item) {
            $fileURL = trim($jobList_more[$key]->getAttribute('href'));
            $checkTender = ParserState::where("link",$fileURL)->where("type_name",$this->type)->first();
            if(is_null($checkTender)){
                $start_date_time = date('Y-m-d H:i:s');
                $name = $jobList_more[$key]->nodeValue;
                // if( strtotime( $startDate ) ==  $now ){
                $competition = 0;
                $parserState = new ParserState;
                $parserState->title = $name;
                $parserState->link = $fileURL;
                $parserState->start_date = $start_date_time;
                $parserState->end_date = date('Y-m-d H:i:s', strtotime('+1 years'));
                $parserState->type_name = $this->type;
                $parserState->is_competition = $competition;
                $parserState->save();
                Log::channel('jobs')->info('TITLE ---'.$name);
                // }
            }
        }
    }
}
