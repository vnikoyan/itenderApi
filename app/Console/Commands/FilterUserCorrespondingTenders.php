<?php

namespace App\Console\Commands;

use App\Models\Settings\UserFilters;
use App\Models\Tender\TenderState;
use App\Models\Tender\TenderStateArchive;
use App\Models\Tender\UserCorrespondingTenders;
use App\Models\User\User;
use App\Models\UserCategories\UserCpvs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FilterUserCorrespondingTenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-corresponding-tenders:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = User::whereHas('cpvs')->get();

        UserCorrespondingTenders::truncate();
        $countNoFilter = 0;
        $countHasFilter = 0;

        foreach ($users as $user) {
            Log::channel('jobs')->info('User --- '.$user->username);
            $user_id = $user->id;
            $filters = UserFilters::where("user_id", $user_id)->first();
            if(!$filters) {
                Log::channel('jobs')->info('With No Filter');
                $countNoFilter++;
                $user_cpvs = array_keys(UserCpvs::where('user_id', $user_id)->get()->keyBy('cpv_id')->toArray());
                $tenders = TenderState::select('id')->where('is_competition', 1)->whereHas('getCpv', function($query) use ($user_cpvs) {
                    $query->whereIn('cpv_id', $user_cpvs);
                })->get();

                $data = [];
                foreach ($tenders as $tender) {
                    $data[] = ['user_id'=> $user_id, 'tender_id'=> $tender->id];
                }
                Log::channel('jobs')->info('Corresponding Tenders --- '.count($tenders));
                Log::channel('jobs')->info('Data --- '.count($data));

                UserCorrespondingTenders::insert($data);
               
            } else {
                Log::channel('jobs')->info('With Filter');
                $filtersDB = $filters;
                // Get Filters
                $type = null;
                $region = null;
                $procedure = null;
                $organizator = null;
                $isElectronic = null;
                $guaranteed = null;
                $status = 'active';
                if($filtersDB){
                    if(json_decode($filtersDB->status)){
                        $status = json_decode($filtersDB->status)->value;
                    }
                    if(json_decode($filtersDB->type)){
                        $type = json_decode($filtersDB->type)->value;
                    }
                    if(json_decode($filtersDB->region)){
                        foreach (json_decode($filtersDB->region) as $value) {
                            $region[] = $value->id;
                        }
                    }
                    if(count(json_decode($filtersDB->procedure))){
                        foreach (json_decode($filtersDB->procedure) as $value) {
                            $procedure[] = $value->id;
                        }
                    }
                    if(count(json_decode($filtersDB->organizator))){
                        foreach (json_decode($filtersDB->organizator) as $value) {
                            $organizator[] = $value->id;
                        }
                    }
                    if(json_decode($filtersDB->isElectronic)){
                        $isElectronic = json_decode($filtersDB->isElectronic)->value ? 'true' : 'false';
                    }
                    if(json_decode($filtersDB->guaranteed)){
                        $guaranteed = json_decode($filtersDB->guaranteed)->value ? '1' : '0';
                    }
                }
                $filters = [
                    "status" => $status,
                    "type" => $type,
                    "region" => $region,
                    "procedure" => $procedure,
                    "organizator" => $organizator,
                    "isElectronic" => $isElectronic,
                    "guaranteed" => $guaranteed
                ];
                // Handle Filters
                Log::channel('jobs')->info('Filters --- '.json_encode($filters));
                $user_cpvs = array_keys(UserCpvs::where('user_id', $user_id)->get()->keyBy('cpv_id')->toArray());
                switch ($filters['status']) {
                    case 'active':
                        $tenders = TenderState::select('id')->where('is_competition', 1)->whereHas('getCpv', function($query) use ($user_cpvs) {
                            $query->whereIn('cpv_id', $user_cpvs);
                        });
                        break;
                    case 'finished':
                        $tenders = TenderStateArchive::select('id')->where('is_competition', 1)->whereHas('getCpv', function($query) use ($user_cpvs) {
                            $query->whereIn('cpv_id', $user_cpvs);
                        });
                        break;
                    case 'all':
                        $live_tenders = TenderState::select('id')->where('is_competition', 1)->whereHas('getCpv', function($query) use ($user_cpvs) {
                            $query->whereIn('cpv_id', $user_cpvs);
                        });
                        $old_tenders = TenderStateArchive::select('id')->where('is_competition', 1)->whereHas('getCpv', function($query) use ($user_cpvs) {
                            $query->whereIn('cpv_id', $user_cpvs);
                        });
                        $tenders = $live_tenders->unionAll($old_tenders);
                        break;
                    default:
                        $tenders = TenderState::select('id')->where('is_competition', 1)->whereHas('getCpv', function($query) use ($user_cpvs) {
                            $query->whereIn('cpv_id', $user_cpvs);
                        });
                        break;
                    }
                if(isset($filters['type'])){
                    $tenders->where('kind', $filters['type']);
                    if($filters['type'] === 'competitive'){
                        if(isset($filters['isElectronic'])){
                            $tenders->where('type', $filters['isElectronic'] ? 'ELECTRONIC' : 'PAPER');
                        }
                        if(isset($filters['procedure']) && is_array($filters['procedure'])){
                            $tenders->whereIn('procedure_type', $filters['procedure']);
                        }
                    }
                }
                if(isset($filters['region'])){
                    $tenders->whereIn('regions', $filters['region']);
                }
                if(isset($filters['organizator'])){
                    $tenders->whereIn('organizer_id', $filters['organizator']);
                }
                if(isset($filters['organizator'])){
                    $tenders->where('guaranteed', $filters['guaranteed']);
                }
                $tenders = $tenders->get();
                $data = [];
                foreach ($tenders as $tender) {
                    $data[] = ['user_id'=> $user_id, 'tender_id'=> $tender->id];
                }
                Log::channel('jobs')->info('Corresponding Tenders --- '.count($tenders));
                Log::channel('jobs')->info('Data --- '.count($data));
                UserCorrespondingTenders::insert($data);
                $countHasFilter++;
            }
            Log::channel('jobs')->info('----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
        }
        Log::channel('jobs')->info('No Filter --- '.$countNoFilter);
        Log::channel('jobs')->info('Has Filter --- '.$countHasFilter);
    }
}
