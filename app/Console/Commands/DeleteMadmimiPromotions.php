<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteMadmimiPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeleteMadmimiPromotions:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting madmimi promotions';

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

        $this->deleteProccess();
        
    }

    public function deleteProccess()
    {
        $promotions = $this->getPromotionsArray();

        if(count($promotions)){
            $ids_array = [];
            foreach ($promotions as $item){
                $ids_array[] = $item['@attributes']['id'];
            }
            foreach ($ids_array as $id) {
                $this->delete($id);
            }
            $this->deleteProccess();
        }
    }

    public function delete($promotion_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.madmimi.com/promotions/{$promotion_id}/trash?username=info@itender.am&api_key=3917df898d73fcc92c642724aba12a35",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER => array(
            'Cookie: _mad_mimi=OU5NNlJnLzNNclE0NmJjUk44c1pwVWxZUExEaU52aHRhd243anRQS1pzclAwSlNxRTkyY3c0UUlONkFSOFQwQkt3WldsekxkMGMxMlZKbGcvV2tJaGRQbEJVZXRJQkorZ2ZZTmpia21lRnM9LS1WMHV5L3lIOUg0OUF1NUZuRTl5bm93PT0%3D--f691aca5dd1c1bbfee47a43c3528374a0607e32e'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

    public function getPromotionsArray()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.madmimi.com/promotions/search.xml?query=%D5%93%D5%B8%D6%83%D5%B8%D5%AD%D5%B8%D6%82%D5%A9%D5%B5%D5%B8%D6%82%D5%B6%D5%B6%D5%A5%D6%80%20%D5%B0%D6%80%D5%A1%D5%BA%D5%A1%D6%80%D5%A1%D5%AF%D5%BE%D5%A1%D5%AE&username=info@itender.am&api_key=3917df898d73fcc92c642724aba12a35',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: _mad_mimi=akpVTFYzQ1NBc0Y0azUzenp3KzJtU3d5dk9sNEtzQXlKVkVTdDh3Q004R1VYSG81aDI3RzdhTUViSlVuN2lxRmZNVGxYWXExT2hRaFVMa2s2QnJTbUYyc1AvRk1Ob0I2b1lKS1orcnJRc1k9LS13dDNFcGxmbWhBOWRXTGI2TWMvODdBPT0%3D--67d4b4320f75213ecabaeb4929a7d6116e4c2479'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        return isset($array['promotion']) ? $array['promotion'] : [];
    }
}
