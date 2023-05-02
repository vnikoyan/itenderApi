<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Cpv\Cpv;
use Illuminate\Support\Facades\Log;

class ClearCpvsPotential implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
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
        $cpvs = Cpv::all();
        foreach ($cpvs as $cpv) {
            if($cpv){
                $amount_empty = [
                    "2022" => '0', 
                    "2023" => '0'
                ];
                $cpv->potential_electronic = json_encode($amount_empty);
                $cpv->save();
            }
        }
    }
}
