<?php
namespace App\Services\Organize;

// Include any required classes, interfaces etc...

use App\Imports\OrganizeRowsImport;
use App\Models\Organize\Organize;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Organize\OrganizeOnePerson;
use App\Models\Organize\OrganizeRow;
use App\Models\Participant\ParticipantRow;
use App\Models\Suggestions\Suggestions;
use App\Models\User\User;
use App\Models\Tender\Organizator;
use App\Models\Tender\TenderState;
use App\Models\Tender\TenderStateCpv;
use App\Models\Settings\UserFilters;
use App\Jobs\ProcessNewTenderAdded;
use App\Jobs\ProcessNewTenderAddedToList;
use Exception;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\TenderCreated;
use Maatwebsite\Excel\Facades\Excel;

class OrganizeOnePersonService
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
     * @return OrganizeOnePerson
     */
    public function updateOrganize($id){
		return $this->edit($id);
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
        return OrganizeOnePerson::insertGetId($request);
	}
    /**
     * @param int $id
     * @return mixed
     */
	private function edit(int $id){
		$organize = OrganizeOnePerson::findOrFail($id);
        foreach ($this->request->all() as $key => $value){
            try {
                $organize->{$key} = $value;
            } catch (Exception $e) {
                return false;
            }
        }
        $organize->save();
        if($organize->publicize == true && $organize->publication == "senden"){
            if($organize->send_to_all_participants == '1'){
                $this->create_tender($organize);
                $this->send_to_all_participants($organize);
            } else if($organize->send_to_all_participants === 'implement-immediately' || $organize->send_to_all_participants === 'from-invoice'){
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
            $suggestion->is_itender = false;
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

    function getRowsFromInvoiceFile($parsedGoods, &$rows){
        if(isset($parsedGoods[0])){
            foreach ($parsedGoods as $index => $good) {
                $rows[] = [
                    'lotNumber' => $index + 1,
                    'cpvCode' => '',
                    'cpvNameArm' => $good['Description'],
                    'cpvNameRu' => '',
                    'specification' => $good['Description'],
                    'specificationRu' => '',
                    'unit' => $good['Unit'],
                    'unitRu' => '',
                    // 'unit_amount' => $good['PricePerUnit'],
                    'unit_amount' => $good['TotalPrice'] / $good['Amount'],
                    'count' => $good['Amount'],
                    'amount' => $good['Price'],
                    'vat' => isset($good['VAT']) ? $good['VAT'] : 0,
                    'total' => $good['TotalPrice'],
                    'fromExcel' => true
                ];
            }
        } else {
            $rows[] = [
                'lotNumber' => 1,
                'cpvCode' => '',
                'cpvNameArm' => $parsedGoods['Description'],
                'cpvNameRu' => '',
                'specification' => $parsedGoods['Description'],
                'specificationRu' => '',
                'unit' => $parsedGoods['Unit'],
                'unitRu' => '',
                'unit_amount' => $parsedGoods['PricePerUnit'],
                'count' => $parsedGoods['Amount'],
                'amount' => $parsedGoods['Price'],
                'vat' => isset($parsedGoods['VAT']) ? $parsedGoods['VAT'] : 0,
                'total' => $parsedGoods['TotalPrice'],
                'fromExcel' => true
            ];
        }
    }

    function getDataFromInvoiceFile($array, $type){
        if(isset($array['SignedData'])) {
            $dataIndex = 'SignedData';
        } else if(isset($array['SignedAccDocData'])) {
            $dataIndex = 'SignedAccDocData';
        } else {
            $dataIndex = 'Invoice';
        }

        $rows = [];
        if(count($array[$dataIndex]) === 1){
            $data = $array[$dataIndex];
            $parsedGoods = $data['Data']['SignableData']['GoodsInfo']['Good'];
            $this->getRowsFromInvoiceFile($parsedGoods, $rows);
        } elseif(isset($array[$dataIndex][0])) {
            $data = $array[$dataIndex][0];
            foreach ($array[$dataIndex] as $data) {
                $parsedGoods = $data['Data']['SignableData']['GoodsInfo']['Good'];
                $this->getRowsFromInvoiceFile($parsedGoods, $rows);
            }
        } else {
            $data = $array[$dataIndex];
            if(isset($data['Data']['SignableData'])){
                $parsedGoods = $data['Data']['SignableData']['GoodsInfo']['Good'];
            } else {
                $parsedGoods = $data['GoodsInfo']['Good'];
            }
            $this->getRowsFromInvoiceFile($parsedGoods, $rows);
        }

        if(isset($data['Data']['SignableData'])){
            $dataObject = $data['Data']['SignableData'];
        } else {
            $dataObject = $data;
        }

        // if($dataIndex === 'SignedData') {
        //     $submission_date = $data['InvoiceMetadata']['SubmissionDate'];
        // } elseif(isset($data['AccDocMetadata'])) {
        //     $submission_date = $data['AccDocMetadata']['SubmissionDate'];
        // } else {
        //     $submission_date = $data['GeneralInfo']['SupplyDate'];
        // }

        


        if($type === 1) {
            $shipping_address_1 = isset($dataObject['BuyerInfo']['DeliveryLocation']) ? $dataObject['BuyerInfo']['DeliveryLocation'] : '';
            $shipping_address_2 = isset($dataObject['BuyerInfo']['DeliveryLocation']) ? $dataObject['SupplierInfo']['SupplyLocation'] : '';
        } else {
            $shipping_address_1 = '';
            $shipping_address_2 = '';
        }

        $participant = [
            'address' => $dataObject['SupplierInfo']['Taxpayer']['Address'],
            'tin' => $dataObject['SupplierInfo']['Taxpayer']['TIN'],
            'name' => $dataObject['SupplierInfo']['Taxpayer']['Name'],
            'account_number' => isset($dataObject['SupplierInfo']['Taxpayer']['BankAccount']['BankAccountNumber']) ? $dataObject['SupplierInfo']['Taxpayer']['BankAccount']['BankAccountNumber'] : '',
            'bank' => isset($dataObject['SupplierInfo']['Taxpayer']['BankAccount']['BankName']) ? $dataObject['SupplierInfo']['Taxpayer']['BankAccount']['BankName'] : '',
        ];
        $code = $dataObject['GeneralInfo']['InvoiceNumber']['Series'] . '-' . $dataObject['GeneralInfo']['InvoiceNumber']['Number'];

        return [
            'rows' => $rows,
            // 'submission_date' => $submission_date,
            'submission_date' => '',
            'shipping_address' => $shipping_address_1 ? $shipping_address_1 : $shipping_address_2,
            'participant' => $participant,
            'code' => $code,
        ];
    }

    public function uploadInvoiceFile($organize_id){
        $organize = OrganizeOnePerson::find($organize_id);
        $allowed =  array('xml');
        $filename = $_FILES['file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) {
            return false;
        }else{
            $xml_name = $_FILES['file']['tmp_name'];
        }
        $xml = simplexml_load_file($xml_name);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        return $result_data = $this->getDataFromInvoiceFile($array, $organize->cpv_type);

        $organize->code = $result_data['code'];
        $organize->shipping_address = $result_data['shipping_address'];
        $organize->delivery_address = $result_data['shipping_address'];
        $organize->send_to_all_participants = 'from-invoice';
        $organize->save();
        unlink($xml_name);

        return $result_data;
        
    }

    public function uploadRowsFile($organize_id){
        $allowed =  array('xlsx');
        $filename = $_FILES['file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) {
            return false;
        }else{
            $path1 = $this->request->file('file')->store('temp'); 
            $path = storage_path('app').'/'.$path1; 
        }
        $rows = Excel::toArray(new OrganizeRowsImport, $path);
        return $rows;
        
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
        $organize = OrganizeOnePerson::find($organize_id);
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
        OrganizeOnePerson::find($organize->id)->update(['create_contract' => 1]);
    }
}
