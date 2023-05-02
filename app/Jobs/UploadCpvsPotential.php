<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Cpv\Cpv;
use Illuminate\Support\Facades\Log;

class UploadCpvsPotential implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        foreach ($this->data as $row) {
            $date = $row[9];
            if($date){
                $input_date_arr = explode("/", $date);
                $year = $input_date_arr[2];
                // $year = date('Y', strtotime($date));
                $cpv_code = explode("/", $row[1])[0];
                $amount_2022 = 0;
                $amount_2023 = 0;
                if(+$year === 2022){
                    $amount_2022 = +$row[8];
                } elseif(+$year === 2023){
                    $amount_2023 = +$row[8];
                }

                
    
                $cpv = Cpv::where('code', $cpv_code)->first();

                if($cpv){
                    $current_potential = json_decode($cpv->potential_electronic, true);
    
                    if($current_potential){
                        $current_amount_2022 = $current_potential['2022'];
                        $current_amount_2023 = $current_potential['2023'];
                        $amount_full = [
                            "2022" => $current_amount_2022 + $amount_2022, 
                            "2023" => $current_amount_2023 + $amount_2023
                        ];
                    } else {
                        $amount_full = [
                            "2022" => $amount_2022, 
                            "2023" => $amount_2023
                        ];
                    }
                    
                    $cpv->potential_electronic = json_encode($amount_full);
                    $cpv->save();
                }
            }
        }
    }
}
