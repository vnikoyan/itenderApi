<?php

namespace App\Console\Commands\UnApprovedType;

use Illuminate\Console\Command;

use Goutte\Client;
use Storage;
use DB;

class UnApprovedType9 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unapproved:type9';

    protected $url      = "http://gnumner.am/";
    protected $phat_hy  = "/hy/page/hraverum_katarvats_popokhutyunner/";
    protected $phat_ru  = "/ru/page/izmeneniya_vnesennye_v_priglashenie/";
    protected $phat_en  = "/en/page/changes_to_the_invitation/";
    protected $type     = "9";
    protected $client   = "";
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Հրավերում կատարված փոփոխություններ';

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
        $this->parsing();
    }

    protected function parsing()
    {                          
        $now = \strtotime( date('Y-m-d') );
        $url = $this->url.$this->phat_hy;
        $crawler = $this->client->request('GET', $url);
        $page  = $crawler->filter('.pagination  a:nth-last-child(2)'); 
        $page_count = 1;
        if ($page->count() > 0 ) {
            $page_count  = $page->text();
        }
        $data = [];
        for ($i=1; $i <= $page_count ; $i++) { 
          $url = $this->url.$this->phat_hy.$i;

          $crawler = $this->client->request('GET', $url);

          $jobList_time = $crawler->filter("p.tender_time");
          $jobList_more = $crawler->filter("div.tender_title p a");

          $index = 0;
          $data[] = $jobList_time->each(function ($node) use ($now,&$jobList_more,&$index,&$i) {
                $hayt_time = $node->text();
                $hayt_time_array = explode(" ", $hayt_time);
                $time = strtotime($hayt_time_array[2]);
             
                // if($time == $now){
                if(true){
                    $jobList_node = $jobList_more->eq($index);

                    $hayt_file = $jobList_node->attr('href');
                    $hayt_title = $jobList_node->text();

                    $start_time = explode("-ից", $hayt_time_array[3]);
                    $hayt_start = $hayt_time_array[2]." ".$start_time[0];
                    if(strpos($hayt_time_array[4], 'անժամկետ)') !== false){
                        $hayt_end = date('Y-m-d H:i:s', strtotime('+1 years'));
                    }else{
                        $hayt_end = $hayt_time_array[5]." ".$hayt_time_array[6];
                    }

                    $url = $this->url.$this->phat_ru.$i;
                    $crawler_ru = $this->client->request('GET', $url);
                    $jobList_more_ru = $crawler_ru->filter("div.tender_title p a")->eq($index);

                    $hayt_file_ru = $jobList_more_ru->attr('href');
                    $hayt_title_ru = $jobList_more_ru->text();

                    $url = $this->url.$this->phat_en.$i;
                    $crawler_en = $this->client->request('GET', $url);
                    $jobList_more_en = $crawler_en->filter("div.tender_title p a")->eq($index);

                    $hayt_file_en = $jobList_more_en->attr('href');
                    $hayt_title_en = $jobList_more_en->text();

                    $link = [
                        "hy" => $hayt_file,
                        "ru" => $hayt_file_ru,
                        "en" => $hayt_file_en
                    ];
                    $title = [
                        "hy" => $hayt_title,
                        "ru" => $hayt_title_ru,
                        "en" => $hayt_title_en
                    ];
                    $index++;
                    return [
                        "start_date" =>  $hayt_start,
                        "end_date"   =>  $hayt_end,
                        "link"       =>  json_encode($link,JSON_UNESCAPED_UNICODE),
                        "title"      =>  json_encode($title,JSON_UNESCAPED_UNICODE),
                        "type"       =>  $this->type,
                    ];
                }
           }); 
        }

        $data_ = [];
        foreach($data as $key => $value){
            foreach($value as $k => $v){
                $data_[] = $v;
            }
        }
        DB::table('tender_state_parser')->insertOrIgnore($data_);

        return true;
    }
}
