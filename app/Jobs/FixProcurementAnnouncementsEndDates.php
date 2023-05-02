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

class FixProcurementAnnouncementsEndDates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tenders = ParserState::where("end_date", '0000-00-00 00:00:00')->get();
        foreach ($tenders as $tender) {
            $end_date = date('Y-m-d H:i:s', strtotime($tender->start_date. '+ 1 years'));
            $tender->end_date = $end_date;
            $tender->save();
        }
    }
}
