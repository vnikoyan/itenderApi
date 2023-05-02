<?php

namespace App\Console\Commands;

use App\Models\Cpv\Cpv;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class GetCpvsPotential extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetCpvsPotential:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Cpvs potential from armeps';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getCpvData($code)
    {
        $curl = curl_init();

        $request_body = '{"query":"'.$code.'"}';
       
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://armeps.am/ppcm/public/cpvs/list',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request_body,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json;charset=UTF-8',
                'Cache-Control: no-store'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function getProcurementsData($cpv_id)
    {
        $curl = curl_init();

        $request_body = '{"filter":{"periods":["9674b1df-ca4c-466c-8ba7-3cf3390791cb"],"authorities":[],"articles":[],"cpvs":["'.$cpv_id.'"],"cpvExtension":null,"forms":[],"units":[],"amountActual":{"min":null,"max":null},"unitValue":{"min":null,"max":null},"quantity":{"min":0,"max":null},"authorityType":null},"page":{"index":0,"size":5000},"order":{"field":"cpv_name_hy","ascending":true}}';

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://armeps.am/ppcm/public/procurements/list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $request_body,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json;charset=UTF-8',
            'Cache-Control: no-store'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cpvs = Cpv::all();
        // $cpvs = Cpv::where('code', 14521171)->get();
        foreach ($cpvs as $cpv) {
            $cpv_data = $this->getCpvData($cpv->code);
            $cpv_data_parsed = json_decode($cpv_data);
            if(isset($cpv_data_parsed->data) && count($cpv_data_parsed->data)){
                $armeps_cpv = $cpv_data_parsed->data[0];
                $procurement_data = $this->getProcurementsData($armeps_cpv->id);
                $procurement_data_parsed = json_decode($procurement_data);
                if(isset($procurement_data_parsed->data) && count($procurement_data_parsed->data)){
                    $procurement_data_array = $procurement_data_parsed->data;
                    $amount_actual = 0;
                    foreach ($procurement_data_array as $procurement_item) {
                        $amount_actual += $procurement_item->amountActual;
                    }
                    $cpv->potential_electronic = $amount_actual;
                    $cpv->save();
                }
            }
        }
    }
}
