<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Organize\OrganizeOnePerson;
use App\Models\Organize\OrganizeRow;
use App\Models\Participant\ParticipantRow;
use App\Models\Participant\ParticipantGroup;
use App\Models\Suggestions\Suggestions;
use Illuminate\Support\Facades\Log;

class ChangeOrganize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Organize:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update organize status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getWinnerParticipant($organize_id, $participants){
        foreach ($participants as $participant) {
            $suggestion = Suggestions::where([
                ['organize_id', $organize_id],
                ['provider_id', $participant->participant_id]
            ])->first();
            if($suggestion && $suggestion->responded){
                return $participant;
            }
        }
        return false;
    }

    public function removeSignatureParticipant($organize_id){
        $suggestions = Suggestions::where([['organize_id', $organize_id], ['responded', 0]])->get();
        foreach ($suggestions as $suggestion) {
            ParticipantGroup::where([['organize_id', $organize_id], ['user_id', $suggestion->provider_id]])->delete();
        }
        return false;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $organizes = OrganizeOnePerson::where('publication','!=','')->get();
        foreach ($organizes as $organize){
            // if(strtotime($organize->opening_date_time) < strtotime(date('Y-m-d H:i:s'))){
            if(strtotime($organize->opening_date_time) < strtotime(date('Y-m-d H:i:s')) && $organize->create_contract === 0){
                $this->removeSignatureParticipant($organize->id);
                if($organize->winner_by_lots){
                    $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participants')->get();
                    foreach ($organize_rows as $organize_row) {
                        if(count($organize_row->participants) && isset($organize_row->participants[0])){
                            $winner = $this->getWinnerParticipant($organize->id, $organize_row->participants);
                            if($winner){
                                $winner_user = $winner->userInfo;
                                $winner_participant_id = $winner->row_group_id;
                                if($winner_user){
                                    $organize_row->winner_user_id = $winner_user->id;
                                }
                                $organize_row->winner_participant_id = $winner_participant_id;
                                $organize_row->won_lot_id = $organize_row->participants[0]->id;
                                $organize_row->save();
                            }
                        }
                    }
                } else {
                    $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participants')->get();
                    $participants = ParticipantRow::where('organize_row_id', $organize_rows[0]->id)
                            ->orderBy('total_price')
                            ->groupBy('participant_id')
                            ->get();
                    $winner = $this->getWinnerParticipant($organize->id, $participants);
                    if($winner){
                        $winner_user = $winner->userInfo;
                        $winner_participant_id = $winner->row_group_id;
                        if($winner_user){
                            $organize->winner_user_id = $winner_user->id;
                        }
                        $organize->winner_participant_id = $winner_participant_id;
                        $organize->winner_user_price = $participants[0]->total_price;
                        $organize->save();
                        foreach ($organize_rows as $organize_row) {
                            $organize_row->winner_participant_id = $winner_participant_id;
                            $won_lot = ParticipantRow::where('organize_row_id', $organize_row->id)
                                ->where('row_group_id', $winner_participant_id)->first();
                            $organize_row->won_lot_id = $won_lot->id;
                            if($winner_user){
                                $organize_row->winner_user_id = $winner_user->id;
                            }
                            $organize_row->save();
                        }
                    }
                }
                OrganizeOnePerson::find($organize->id)->update(['create_contract' => 1]);
            }else{
                continue;
            }
        }
    }
}
