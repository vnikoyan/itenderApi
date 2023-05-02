<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Organize\OrganizeOnePerson;
use App\Models\Tender\TenderState;
use App\Models\Tender\TenderStateCpv;
use Illuminate\Support\Facades\Log;

class OrganizeSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TenderState:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'will take organize data and create new tender states';

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
        $date = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")) -3600);
        $organize = OrganizeOnePerson::select("organize.*","organize.name","organize.updated_at as upDate","organize.opening_date_time","organize.code")
                                     ->with("organizeRows")
                                     ->with("user")
                                     ->where("organize.updated_at","LIKE","%".date("Y-m-d")."%")
                                     ->get();

        foreach ($organize as $key => $value) {
            if( strtotime($value->upDate) <= strtotime(date("Y-m-d H:i:s")) && strtotime($value->upDate) >= strtotime($date)){
                $cpv = array();
                $totalPrice = 0;
                foreach($value->organizeRows as $val){
                    $totalPrice += $val->count * $val->procurementPlan->details[0]->unit_amount;
                }

                $tenderState = new TenderState;
                $tenderState->one_person_organize_id = $value->id;
                $tenderState->title = $value->name;
                $tenderState->link = null;
                $tenderState->start_date = $value->upDate;
                $tenderState->end_date = $value->opening_date_time;
                $tenderState->contract_html = $value->contract_html_hy;
                $tenderState->ministry = 0;
                $tenderState->state_institution = 0;
                $tenderState->regions = 0;
                $tenderState->type = "ELECTRONIC";
                $tenderState->tender_type = 1;
                $tenderState->is_million10 = null;
                $tenderState->is_competition = null;
                $tenderState->is_new = null;
                $tenderState->is_closed = null;
                $tenderState->estimated = $totalPrice;
                $tenderState->is_competition = 1;
                $tenderState->estimated_file = null;
                $tenderState->customer_name ='«'.$organize->user->organisation->name.'» '.$organize->user->organisation->company_type;
                $tenderState->password = $value->code;
                $tenderState->created_at = date("Y-m-d H:i:s");
                $tenderState->updated_at = date("Y-m-d H:i:s");
                $tenderState->kind = "one_person";
                $tenderState->save();

                foreach($value->organizeRows as $val){
                    $tenderStateCpv = new TenderStateCpv;
                    $tenderStateCpv->view_id = $val->view_id;
                    $tenderStateCpv->cpv_id = $val->cpv->id;
                    $tenderStateCpv->tender_state_id = $tenderState->id;
                    $tenderStateCpv->save();
                    array_push($cpv, strval($val->cpv->id));
                }

                $tenderState->cpv = json_encode($cpv);
                $tenderState->save();
            }

        }
    }
}
