<?php
namespace App\Services\Organize;

// Include any required classes, interfaces etc...

use App\Models\Organize\Organize;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Organize\OrganizeItender;
use App\Models\Organize\OrganizeRow;
use App\Models\Participant\ParticipantRow;
use App\Models\Suggestions\Suggestions;
use App\Models\User\User;
use App\Models\Tender\Organizator;
use App\Models\Tender\TenderState;
use App\Models\Settings\UserFilters;
use App\Models\Tender\TenderStateCpv;
use App\Jobs\ProcessNewTenderAdded;
use App\Jobs\ProcessNewTenderAddedToList;
use App\Models\Participant\ParticipantGroup;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class OrganizeItenderService
{
	use DispatchesJobs;

	/**
	 * Incoming HTTP Request.
	 *
	 * @var Request;
    */
	protected  $request;

	/**
	 *
	 * @param Request $request
	*/
	public function __construct(Request $request){
		$this->request = $request;
	}

    public function createOrganize(){
		return $this->bilder();
	}

    /**
     * @param $id
     * @return OrganizeItender
     */
    public function updateOrganize($id){
		return $this->edit($id);
	}

    /**
     * @param $id
     * @return OrganizeItender
     */
    public function setReportDocument($id){
		$organize = OrganizeItender::findOrFail($id);
        $organize->report_document = $this->request->get('report_document');
        $organize->save();
        return $organize;
	}
    /**
     * @param $id
     * @return OrganizeItender
     */
    public function setWinnerOrganize($id){
        $organize = OrganizeItender::findOrFail($id);
        $organize_rows = OrganizeRow::where('organize_id', $organize->id)->get();

        if($organize->winner_by_lots){
            $winner_rows = $this->request->get('winnerRows');
            foreach ($organize_rows as $organize_row) {
                $winner_participant_id = $winner_rows[$organize_row->id];
                $winner_participant = ParticipantGroup::find($winner_participant_id);
                $winner_user_id = $winner_participant->user_id;
                if($organize_row){
                    $won_lot = ParticipantRow::where('organize_row_id', $organize_row->id)
                    ->where('row_group_id', $winner_participant_id)->where('is_satisfactory', 1)->first();
                    $organize_row->winner_participant_id = $winner_participant_id;
                    $organize_row->winner_user_id = $winner_user_id;
                    $organize_row->won_lot_id = $won_lot->id;
                    $organize_row->save();
                }
            }
        } else {
            $winner_participant_id = $this->request->get('winner');
            $winner_participant = ParticipantGroup::find($winner_participant_id);
            $winner_user_id = $winner_participant->user_id;
            foreach ($organize_rows as $organize_row) {
                $winner_user_price = 0;
                if($organize_row){
                    $won_lot = ParticipantRow::where('organize_row_id', $organize_row->id)
                        ->where('row_group_id', $winner_participant_id)->where('is_satisfactory', 1)->first();
                    $organize_row->winner_participant_id = $winner_participant_id;
                    $organize_row->winner_user_id = $winner_user_id;
                    $organize_row->won_lot_id = $won_lot->id;
                    $organize_row->save();
                    $winner_user_price = $won_lot->total_price;
                }
            }
            $organize->winner_user_id = $winner_user_id;
            $organize->winner_participant_id = $winner_participant_id;
            $organize->winner_user_price = $winner_user_price;
            $organize->save();
        }
        return $organize;
	}
    /**
     * @param $id
     * @return OrganizeItender
     */
    public function evalutionOrganize($id){
		$organize = OrganizeItender::findOrFail($id);
        $organize->winner_user_id = 0;
        $organize->winner_user_price = 0;
        $organize->winner_participant_id = 0;
        $organize->save();
        $request = $this->request->all();
        if($organize->winner_by_lots){
            foreach ($request as $participant) {
                foreach ($participant['lots'] as $lot) {
                    foreach ($participant['lots'] as $lot) {
                        $organize_row = OrganizeRow::where('won_lot_id', $lot['id'])->first();
                        if($organize_row){
                            $organize_row->winner_participant_id = 0;
                            $organize_row->winner_user_id = 0;
                            $organize_row->won_lot_id = 0;
                            $organize_row->winner_lot_trademark = '';
                            $organize_row->winner_lot_brand = '';
                            $organize_row->winner_lot_manufacturer = '';
                            $organize_row->winner_lot_specification = '';
                            $organize_row->save();
                        }
                        $row = ParticipantRow::find($lot['id']);
                        $row->is_satisfactory = $lot['is_satisfactory'];
                        $row->rejection_reason = $lot['rejection_reason'];
                        $row->save();
                    }
                }
            }
        } else {
            foreach ($request as $participant) {
                $is_satisfactory = $participant['lots'][0]['is_satisfactory'];
                $rejection_reason = $participant['lots'][0]['rejection_reason'];
                foreach ($participant['lots'] as $lot) {
                    $organize_row = OrganizeRow::where('won_lot_id', $lot['id'])->first();
                    if($organize_row){
                        $organize_row->winner_participant_id = 0;
                        $organize_row->winner_user_id = 0;
                        $organize_row->won_lot_id = 0;
                        $organize_row->winner_lot_trademark = '';
                        $organize_row->winner_lot_brand = '';
                        $organize_row->winner_lot_manufacturer = '';
                        $organize_row->winner_lot_specification = '';
                        $organize_row->save();
                    }

                    $row = ParticipantRow::find($lot['id']);
                    $row->is_satisfactory = $is_satisfactory;
                    $row->rejection_reason = $rejection_reason;
                    $row->save();
                }
            }
        }
        $this->decisionWinner($organize);
        return $organize;
	}

    public function decisionWinner($organize){
        $user = User::find($organize->user_id);
        $without_vat = $user->vat_payer_type === 'not_payer' ? true : false;
        if($without_vat){
            if($organize->winner_by_lots){
                $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participantsOrderByCost')->get();
                foreach ($organize_rows as $organize_row) {
                    if(count($organize_row->participantsOrderByCost) && isset($organize_row->participantsOrderByCost[0])){
                        $winner = $this->getWinnerParticipant($organize->id, $organize_row->participantsOrderByCost);
                        if($winner){
                            $winner_user = $winner->userInfo;
                            $winner_participant_id = $winner->row_group_id;
                            if($winner_user){
                                $organize_row->winner_user_id = $winner_user->id;
                            }
                            $organize_row->winner_participant_id = $winner_participant_id;
                            $organize_row->won_lot_id = $organize_row->participantsOrderByCost[0]->id;
                            $organize_row->save();
                        }
                    }
                }
            } else {
                $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participantsOrderByCost')->get();
                $participantsOrderByCost = ParticipantRow::where('organize_row_id', $organize_rows[0]->id)
                        ->orderBy('total_price')
                        ->groupBy('participant_id')
                        ->where('is_satisfactory', 1)
                        ->get();
                $winner = $this->getWinnerParticipant($organize->id, $participantsOrderByCost);
                if($winner){
                    $winner_user = $winner->userInfo;
                    $winner_participant_id = $winner->row_group_id;
                    if($winner_user){
                        $organize->winner_user_id = $winner_user->id;
                    }
                    $organize->winner_participant_id = $winner_participant_id;
                    $organize->winner_user_price = $participantsOrderByCost[0]->total_price;
                    $organize->save();
                    foreach ($organize_rows as $organize_row) {
                        $organize_row->winner_participant_id = $winner_participant_id;
                        $won_lot = ParticipantRow::where('organize_row_id', $organize_row->id)
                            ->where('row_group_id', $winner_participant_id)->where('is_satisfactory', 1)->first();
                        $organize_row->won_lot_id = $won_lot->id;
                        if($winner_user){
                            $organize_row->winner_user_id = $winner_user->id;
                        }
                        $organize_row->save();
                    }
                }
            }
        } else {
            if($organize->winner_by_lots){
                $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participantsOrderByValue')->get();
                foreach ($organize_rows as $organize_row) {
                    if(count($organize_row->participantsOrderByValue) && isset($organize_row->participantsOrderByValue[0])){
                        $winner = $this->getWinnerParticipant($organize->id, $organize_row->participantsOrderByValue);
                        if($winner){
                            $winner_user = $winner->userInfo;
                            $winner_participant_id = $winner->row_group_id;
                            if($winner_user){
                                $organize_row->winner_user_id = $winner_user->id;
                            }
                            $organize_row->winner_participant_id = $winner_participant_id;
                            $organize_row->won_lot_id = $organize_row->participantsOrderByValue[0]->id;
                            $organize_row->save();
                        }
                    }
                }
            } else {
                $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participantsOrderByValue')->get();
                $participantsOrderByValue = ParticipantRow::where('organize_row_id', $organize_rows[0]->id)
                        ->orderBy('total_price')
                        ->groupBy('participant_id')
                        ->where('is_satisfactory', 1)
                        ->get();
                $winner = $this->getWinnerParticipant($organize->id, $participantsOrderByValue);
                if($winner){
                    $winner_user = $winner->userInfo;
                    $winner_participant_id = $winner->row_group_id;
                    if($winner_user){
                        $organize->winner_user_id = $winner_user->id;
                    }
                    $organize->winner_participant_id = $winner_participant_id;
                    $organize->winner_user_price = $participantsOrderByValue[0]->total_price;
                    $organize->save();
                    foreach ($organize_rows as $organize_row) {
                        $organize_row->winner_participant_id = $winner_participant_id;
                        $won_lot = ParticipantRow::where('organize_row_id', $organize_row->id)
                            ->where('row_group_id', $winner_participant_id)->where('is_satisfactory', 1)->first();
                        $organize_row->won_lot_id = $won_lot->id;
                        if($winner_user){
                            $organize_row->winner_user_id = $winner_user->id;
                        }
                        $organize_row->save();
                    }
                }
            }
        }
        OrganizeItender::find($organize->id)->update(['create_contract' => 1]);
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
    

    /**
     * @return mixed
    */
	private function bilder():int {
        $request = $this->request->all();
        $request["created_at"] = date("Y-m-d h:i:s");
        if(!empty($request['name'])){
            $request["name"]      = json_encode($request['name']);
            $request["code"]      = json_encode($request['code']);
        }
        $request["user_id"] = auth('api')->user()->id;
        if(!empty($request['evaluator_member'])){
            $request["evaluator_member"]         = json_encode($request['evaluator_member']);
            $request["evaluator_president"]      = json_encode($request['evaluator_president']);
            $request["evaluator_secretary_name"] = json_encode($request['evaluator_secretary_name']);
        }
        return OrganizeItender::insertGetId($request);
	}
    /**
     * @param int $id
     * @return mixed
     */
	private function edit(int $id){
		$organize = OrganizeItender::findOrFail($id);
        if($organize->publicize == true && $organize->publication == "senden"){
            $organize->itender_edited = true;
            $organize->save();
        }
        foreach ($this->request->all() as $key => $value){
            try {
                $organize->{$key} = $value;
            } catch (Exception $e) {
                return false;
            }
        }
        $organize->save();
        if($organize->publicize == true && $organize->publication == "senden"){
            $organize->send_date = Carbon::now();
            $organize->save();
            if($organize->send_to_all_participants == '1'){
                $this->create_tender($organize);
                $this->send_to_all_participants($organize);
            } else if($organize->send_to_all_participants == 'implement-immediately'){
                $this->get_winner($organize->id);
            } else {
                $this->send_to_selected_participants($organize);
            }
        }
        return $organize;
	}

    function create_tender($organize){
        $cpv = array();
        $totalPrice = 0;
        foreach($organize->organizeRows as $val){
            $totalPrice += $val->count * $val->procurementPlan->details[0]->unit_amount;
        }

        $tenderState = new TenderState;
        $tenderState->one_person_organize_id = $organize->id;
        $tenderState->title = $organize->name;
        $tenderState->link = null;
        $tenderState->start_date = $organize->updated_at;
        $tenderState->end_date = $organize->opening_date_time;
        $tenderState->contract_html = $organize->contract_html_hy;
        $tenderState->ministry = 0;
        $tenderState->state_institution = 0;
        $tenderState->regions = 0;
        $tenderState->type = "ELECTRONIC";
        $tenderState->tender_type = 1;
        $tenderState->is_million10 = null;
        $tenderState->is_competition = null;
        $tenderState->is_new = null;
        $tenderState->is_closed = 1;
        $tenderState->estimated = $totalPrice;
        $tenderState->is_competition = 1;
        $tenderState->estimated_file = null;
        $tenderState->customer_name ='«'.$organize->user->organisation->name.'» '.$organize->user->organisation->company_type;
        $tenderState->password = $organize->code;
        $tenderState->created_at = date("Y-m-d H:i:s");
        $tenderState->updated_at = date("Y-m-d H:i:s");
        $tenderState->kind = "one_person";
        $tenderState->save();

        foreach($organize->organizeRows as $val){
            $cpv_name = $val->is_from_outside ? $val->procurementPlan->cpvOutside->name : $val->procurementPlan->cpv->name;
            $cpv_code = $val->is_from_outside ? $val->procurementPlan->cpvOutside->code : $val->procurementPlan->cpv->code;
            $tenderStateCpv = new TenderStateCpv;
            $tenderStateCpv->cpv_name = $val->is_from_outside ? $val->procurementPlan->cpvOutside->name : $val->procurementPlan->cpv->name;
            $tenderStateCpv->cpv_code = $val->is_from_outside ? $val->procurementPlan->cpvOutside->code : $val->procurementPlan->cpv->code;
            $tenderStateCpv->view_id = $val->view_id;
            $tenderStateCpv->cpv_name = $cpv_name;
            $tenderStateCpv->cpv_code = $cpv_code;
            $tenderStateCpv->cpv_id = $val->cpv ? $val->cpv->id : 0;
            $tenderStateCpv->tender_state_id = $tenderState->id;
            $tenderStateCpv->save();
            $val->cpv && array_push($cpv, strval($val->cpv->id));
        }

        $tenderState->cpv = json_encode($cpv);
        $tenderState->save();
        $url = \Config::get('values')['frontend_url'].'/participant/tenders/?id='.$tenderState->id;
        $data = new \stdClass();
        $data->subject = "Նոր տենդեր iTender համակարգում";
        $password = ($organize->code == null) ? 'առանց ծածկագրի' : $organize->code;
        if( $password != null){
            $data->subject = "Նոր տենդեր iTender համակարգում: Ծածկագիրը՝ $password";
        }
        $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                        Գնման առարկան` ".htmlentities($organize->name)."
                        </div>
                        <div style='display: none; max-height: 0px; overflow: hidden;'>
                        &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                    </div>
                        <p>Պատվիրատուն՝ ".$tenderState->customer_name."</p><br>
                        <p>Գնման առարկան՝ ".htmlentities($organize->name)."</p></br>
                        <p>Ծածկագիրը՝ ".$password."</p></br>
                        <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($organize->updated_at))."</p></br>
                        <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($organize->opening_date_time))."</p>";   
                        if(trim($organize->send_to_all_participants) != "implement-immediately"){
                            $data->text.= " <a href = '".$url."'>Տեսնել</a></br><p>Հարգանքով՝ iTender թիմ</p>";
                        }else{
                            $data->text.= "<p>Հարգանքով՝ iTender թիմ</p>";
                        }
        ProcessNewTenderAddedToList::dispatch($cpv, $tenderState, $data);
    }

    function send_to_all_participants($organize){
        $organize_id = $organize->id;
        Suggestions::where('organize_id', $organize_id)->delete();
        $organize_rows = OrganizeRow::where('organize_id', $organize_id)->with('cpv')->get();
        $participants_ids = [];
        foreach ($organize_rows as $row) {
            if($row->cpv){
                $participants = $row->cpv->participants;
                foreach ($participants as $participant) {
                    $email = 0;
                    $filterCount = 0;
                    $filters = UserFilters::where("user_id",$participant->id)->first();
                    if(is_null($filters)){
                        array_push($participants_ids, $participant->id);
                    }else{
                        $status = (isset(json_decode($filters->status)->value)) ? json_decode($filters->status)->value : null;
                        $isElectronic = (isset(json_decode($filters->isElectronic)->value)) ? (json_decode($filters->isElectronic)->value) ? 'ELECTRONIC' : 'PAPER' : null; 
                        $filerKind =  (isset(json_decode($filters->type)->value) ) ? json_decode($filters->type)->value : null;
                        (isset(json_decode($filters->type)->value) ) ? $filterCount++ : $filterCount ;
                        (isset(json_decode($filters->isElectronic)->value)) ? $filterCount++ : $filterCount;
                        (isset(json_decode($filters->status)->value) ) ? $filterCount++ : $filterCount; 
                        if($status == "active"){
                            $email++;
                        }
                        if($isElectronic == "ELECTRONIC"){
                            $email++;
                        }
                        if($filerKind == "private"){
                            $email++;
                        }
                        if($email == $filterCount){
                            array_push($participants_ids, $participant->id);
                        }
                    }
                }
            }
        }
        $participants_ids_unique = array_unique($participants_ids);
        foreach ($participants_ids_unique as $participants_id) {
            $participant = User::find($participants_id);
            $suggestion = new Suggestions();
            $suggestion->client_id = auth('api')->user()->id;
            $suggestion->provider_id = $participants_id;
            $suggestion->organize_id = $organize_id;
            $suggestion->is_itender = true;
            $suggestion->save();
            $data = new \stdClass();
            $data->email = trim($participant->email);
            $password = ($organize->code == null) ? 'առանց ծածկագրի' : $organize->code;
            if( $password != null){
                $data->subject = "Նոր առաջարկ iTender համակարգում: Ծածկագիրը՝ $password";
            }
            $url = \Config::get('values')['frontend_url'].'/participant/suggestions/all';
            $customer = $organize->user->organisation->id_card_number ? $organize->user->organisation->name : ('«'.$organize->user->organisation->name.'» '.$organize->user->organisation->company_type);
            $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                            Մրցույթի անվանում` ".htmlentities($organize->name)."
                            </div>
                            <div style='display: none; max-height: 0px; overflow: hidden;'>
                            &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                            </div>
                            <p>Պատվիրատուն՝ ".$customer."</p><br>
                            <p>Մրցույթի անվանում՝ ".htmlentities($organize->name)."</p></br>
                            <p>Ծածկագիրը՝ ".$password."</p></br>
                            <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($organize->updated_at))."</p></br>
                            <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($organize->opening_date_time))."</p>";   
                            $data->text.= " <a href = '".$url."'>Մասնակցել</a></br><p>Հարգանքով՝ iTender թիմ</p>";
            $user = $participant;
            if($user->email_notifications){
                ProcessNewTenderAdded::dispatch($data);
            }
            if($user->telegram_notifications){
                $subject = substr($data->subject, 0, strpos($data->subject, ":"));
                $content = "*$subject*
    
Պատվիրատուն՝ *".$customer."*
Մրցույթի անվանում՝ *".$organize->name."*
Ծածկագիրը՝ *".$password."*
Սկիզբ՝ *".date("Y-m-d H:i",strtotime($organize->updated_at))."*
Վերջնաժամկետը՝ *".date("Y-m-d H:i",strtotime($organize->opening_date_time))."*
                ";
                Notification::send($user, new SendNotification($user->id, $content, $url));
            }
            $notification_data = [
                'customer' => $customer,
                'type' => 'suggestion_request',
                'subject' => $data->subject,
                'tender_id' => 0
            ];
            // $user->notify(new TenderCreated($notification_data));
        }
    }

    function send_to_selected_participants($organize){
        $organize_id = $organize->id;
        $suggestions = Suggestions::where('organize_id', $organize_id)->with('provider')->get();
        $organize_rows = OrganizeRow::where('organize_id', $organize_id)->with('cpv')->get();
        $participants_ids = [];
        $participants_ids_unique = array_unique($participants_ids);
        foreach ($suggestions as $suggestion) {
            $email = $suggestion->provider->email;
            $data = new \stdClass();
            $data->email = trim($email);
            $password = ($organize->code == null) ? 'առանց ծածկագրի' : $organize->code;
            if( $password != null){
                $data->subject = "Նոր առաջարկ iTender համակարգում: Ծածկագիրը՝ $password";
            }
            $url = \Config::get('values')['frontend_url'].'/participant/suggestions/all';
            $customer = $organize->user->organisation->id_card_number ? $organize->user->organisation->name : ('«'.$organize->user->organisation->name.'» '.$organize->user->organisation->company_type);
            $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                            Մրցույթի անվանում` ".htmlentities($organize->name)."
                            </div>
                            <div style='display: none; max-height: 0px; overflow: hidden;'>
                            &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                            </div>
                            <p>Պատվիրատուն՝ ".$customer."</p><br>
                            <p>Մրցույթի անվանում՝ ".htmlentities($organize->name)."</p></br>
                            <p>Ծածկագիրը՝ ".$password."</p></br>
                            <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($organize->updated_at))."</p></br>
                            <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($organize->opening_date_time))."</p>";   
                            $data->text.= " <a href = '".$url."'>Մասնակցել</a></br><p>Հարգանքով՝ iTender թիմ</p>";
                            
            $user = User::find($suggestion->provider_id);
            if($user->email_notifications){
                ProcessNewTenderAdded::dispatch($data);
            }
            if($user->telegram_notifications){
                $subject = substr($data->subject, 0, strpos($data->subject, ":"));
                $content = "*$subject*
    
Պատվիրատուն՝ *".$customer."*
Մրցույթի անվանում՝ *".$organize->name."*
Ծածկագիրը՝ *".$password."*
Սկիզբ՝ *".date("Y-m-d H:i",strtotime($organize->updated_at))."*
Վերջնաժամկետը՝ *".date("Y-m-d H:i",strtotime($organize->opening_date_time))."*
                ";
                Notification::send($user, new SendNotification($user->id, $content, $url));
            }
            $notification_data = [
                'customer' => $customer,
                'type' => 'suggestion_request',
                'subject' => $data->subject,
                'tender_id' => 0
            ];
            // $user->notify(new TenderCreated($notification_data));
        }
    }

    function get_winner($organize_id){
        $organize = OrganizeItender::find($organize_id);
        if($organize->winner_by_lots){
            $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participants')->get();
            foreach ($organize_rows as $organize_row) {
                if(count($organize_row->participants) && isset($organize_row->participants[0])){
                    $winner_user = $organize_row->participants[0]->userInfo;
                    $winner_participant_id = $organize_row->participants[0]->row_group_id;
                    if($winner_user){
                        $organize_row->winner_user_id = $winner_user->id;
                    }
                    $organize_row->winner_participant_id = $winner_participant_id;
                    $organize_row->won_lot_id = $organize_row->participants[0]->id;
                    $organize_row->save();
                }
            }
        } else {
            $organize_rows = OrganizeRow::where('organize_id', $organize->id)->with('participants')->get();
            $participants = ParticipantRow::where('organize_row_id', $organize_rows[0]->id)
                    ->orderBy('total_price')
                    ->groupBy('participant_id')
                    ->get();
            if(isset($participants[0])){
                $winner_participant_id = $participants[0]->row_group_id;
                $organize->winner_participant_id = $winner_participant_id;
                $winner_user = $participants[0]->userInfo;
                if($winner_user){
                    $organize->winner_user_id = $winner_user->id;
                }
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
        OrganizeItender::find($organize->id)->update(['create_contract' => 1]);
    }
}
