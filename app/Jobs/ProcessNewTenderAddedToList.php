<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\Api\Mail\MailController;
use App\Models\Cpv\Cpv;
use Illuminate\Support\Facades\Log;

class ProcessNewTenderAddedToList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $cpvs, $tenderState, $data, $list;

    public function __construct($cpvs, $tenderState, $data)
    {
        $this->cpvs = $cpvs;
        $this->tenderState = $tenderState;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->getMadmimiList();
        $lists = [];
        foreach ($this->cpvs as $cpvId) {
            $cpv = Cpv::find($cpvId);
            $list = $this->getCurrentMadmimiList($cpv);
            if($list){
                $lists[] = $list;
            }
        }
        foreach ($lists as $list) {
            $this->tenderState->participants_count = $this->tenderState->participants_count + $list['subscriber_count'];
            $this->tenderState->save();
            $this->sendToList($list);
        }
    }

    public function sendToList($list)
    {
        $data = $this->data;

        $arrayToList = array(
            "username"=>'info@itender.am',
            "api_key"=>'3917df898d73fcc92c642724aba12a35',
            "promotion_name"=> $data->subject.' to list',
            "subject"=> $data->subject,
            "list_name" => 'test',
            // "list_name" => $list['name'],
            "from"=>'iTender <info@itender.am>',
            "raw_html"=>app('App\Http\Controllers\Api\Mail\MailController')->mailView($data->text)
        );
        $toList = curl_init('https://api.madmimi.com/mailer/to_list');
        
        curl_setopt($toList, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($toList, CURLOPT_POSTFIELDS, $arrayToList);
        $result = curl_exec($toList);
        curl_close($toList);
    }


    public function getCurrentMadmimiList($cpv)
    {
        $current_list = false;
        if($cpv && $cpv->code){
            foreach ($this->list as $item) {
                $pos = strpos($item['@attributes']['name'], $cpv->code);
                if($pos){
                    $current_list = $item['@attributes'];
                } 
            }
            if($current_list){
                return $current_list;
            } else {
                $cpvObj = Cpv::find($cpv->id);
                $parent = $cpvObj->parent;
                if(count($parent)){
                    $cpvObj = Cpv::find($parent[0]->id);
                    return $this->getCurrentMadmimiList($cpvObj);
                } else {
                    return false;
                }
            }
        }
    }



    public function getMadmimiList()
    {
        $ch = curl_init();
        $url = "https://api.madmimi.com/audience_lists/lists.xml";
        $dataArray = array(
            "username"=>'info@itender.am',
            "api_key"=>'3917df898d73fcc92c642724aba12a35',
        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        $result = curl_exec($ch);
        $errors = curl_error($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        curl_close($ch);
        $this->list = $array['list'];
    }
}
