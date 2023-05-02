<?php


namespace App\Services\Participant;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\Participant\Participant;
use App\Models\Participant\ParticipantRow;
use App\Models\Participant\ParticipantGroup;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ParticipantGroupService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
     */
    protected $request;
    /** * User Service Class Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function createParticipantGroup(){
        return json_encode($this->builder('full'));
    }

    public function createInvoiceParticipantGroup(){
        return json_encode($this->builder('half'));
    }

    public function processXML(){
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
        $array = json_decode($json,TRUE);
        $response = array();
        foreach ($array['lot'] as $item) {
            if($item["@attributes"]["included-in-submission"] === 'yes'){
                $pattern_new = '/(\d+)/';
                $value = $item['envelope'][1]["criterion"]['prices']['price'];
                preg_match($pattern_new, $item['title'], $vals);
                $key = $vals[0];
                if($key){
                    array_push($response, ['view_id' => $key, 'value' => $value]);
                }
            }
        }
        return $response;
        unlink($xml_name);
    }

    public function processXMLandZIP(){
        $allowed =  array('xml','zip');
        $filename = $_FILES['file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) {
            return false;
        }else{
            if($ext == 'xml'){
                $xml_name = $_FILES['file']['tmp_name'];
            }else{
                $xml_name = '';
                $archive = $_FILES['file']['tmp_name'];
                $zip = new ZipArchive();
                $res = $zip->open($archive);
                if ($res === true){
                    if (!file_exists(getcwd().'/xml')) {
                        mkdir(getcwd().'/xml', 0777, true);
                    }

                    for($i = 0; $i < $zip->numFiles; $i++)
                    {
                        $OnlyFileName = $zip->getNameIndex($i);
                        $FullFileName = $zip->statIndex($i);

                        if (!($FullFileName['name'][strlen($FullFileName['name'])-1] =="/"))
                        {
                            if (preg_match('#\.(xml)$#i', $OnlyFileName))
                            {
                                copy('zip://'. $archive .'#'. $OnlyFileName , getcwd().'/xml/'.$FullFileName['name'] );
                                $xml_name = getcwd().'/xml/'.$FullFileName['name'];
                            }
                        }
                    }

                    //$unzip_success= $zip->extractTo(getcwd().'/xml/','test.xml');
                    $zip->close();

                }
            }
        }
        $xml = simplexml_load_file($xml_name);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        $response = array();
        if(isset($array['lot'])){
            foreach ($array['lot'] as $item) {
                if($item["@attributes"]["included-in-submission"] === 'yes'){
                    $pattern_new = '/(\d+)/';
                    if(count($item['envelope'][1]["criterion"]) === 2){
                        $value = $item['envelope'][1]["criterion"][1]['prices']['price'];
                    } else {
                        $value = $item['envelope'][1]["criterion"]['prices']['price'];
                    }
                    preg_match($pattern_new, $item['title'], $vals);
                    $key = $vals[0];
                    if($key){
                        array_push($response, ['view_id' => $key, 'value' => $value]);
                    }
                }
            }
        } else {
            $value = $array['envelope'][2]["criterion"]['prices']['price'];
            array_push($response, ['view_id' => 1, 'value' => $value]);
        }
        return $response;
        unlink($xml_name);
    }

    public function addPersonalInfo(int $id){
        $participant = ParticipantGroup::findOrFail($id);
        if($this->request->account_number){
            $participant->account_number = $this->request->account_number;
        }
        if($this->request->director){
            $participant->director = $this->request->director;
        }
        if($this->request->bank){
            $participant->bank = $this->request->bank;
        }
        $participant->save();
    }

    public function updateParticipantGroup(int $id):void{
        $participant = ParticipantGroup::findOrFail($id);

        if($this->request->is_agency_agreement){
            $participant->is_agency_agreement = $this->request->is_agency_agreement;
        }
        if($this->request->is_cooperation){
            $participant->is_cooperation = $this->request->is_cooperation;
        }
        $participant->save();
        ParticipantRow::where('row_group_id', $participant->id)->delete();
        Participant::where('group_id', $participant->id)->delete();
        if(isset($this->request->lots)){
            $insertArrayLots = [];
            foreach($this->request->lots as $key => $lot){
                $insertArrayLots[$key] = [
                    "row_group_id" => $participant->id,
                    "organize_row_id" => $lot['organize_row_id'],
                    "cost" => $lot['overall'], 
                    "is_satisfactory" => $lot['is_satisfactory'], 
                    "value" => $lot['total_price'], 
                    "vat" => $lot['vat'],
                    "specification" => isset($lot['specification']) ? $lot['specification'] : '',
                    "get_response" => isset($lot['get_response']) ? $lot['get_response'] : 0,
                    "total_price" => isset($lot['total']) ? $lot['total'] : null,
                    "participant_id" => isset($lot['participant_id']) ? $lot['participant_id'] : null,
                ];
            }
            ParticipantRow::insert($insertArrayLots);
        }
        $insertArray = [];
        foreach($this->request->participant as $key => $request){
            $insertArray[$key] = [
                "group_id" => $participant->id,
                "organize_id" => $this->request->organize_id,
                "email" => $request['email'],
                "phone" => $request['phone'],
                "tin" => $request['tin'],
                "is_docs_satisfactory" => $request['is_docs_satisfactory'],
                "price_offer_exists" => $request['price_offer_exists'],
                "date_of_submission" => $request['date_of_submission'],
                "is_physical_person" => $request['is_physical_person'],
                "id_card_number" => $request['id_card_number'],
                "first_name" => json_encode($request['first_name']),
                "last_name" => json_encode($request['last_name']),
                "middle_name" => json_encode($request['middle_name']),
                "name" => json_encode($request["name"]),
                "address" => json_encode($request["address"]),
            ];
        }
        Participant::insert($insertArray);
    }

    public function builder($type){
        $group = new ParticipantGroup;
        $group->organize_id = $this->request->organize_id;
        $group->is_agency_agreement = $this->request->is_agency_agreement;
        $group->is_cooperation = $this->request->is_cooperation;
        $group->user_id = $this->request->user_id;
        if($type === 'half'){
            $group->bank = $this->request->bank;
            $group->account_number = $this->request->account_number;
        }
        $group->save();

        $group_id=$group->id;

        if(isset($this->request->lots)){
            $insertArrayLots = [];
            foreach($this->request->lots as $key => $lot){
                $insertArrayLots[$key] = [
                    "row_group_id" => $group_id,
                    "organize_row_id" => isset($lot['organize_row_id']) ? $lot['organize_row_id'] : null,
                    "cost" => isset($lot['overall']) ? $lot['overall'] : (isset($lot['overall']) ? $lot['overall'] : null),
                    "is_satisfactory" => isset($lot['is_satisfactory']) ? $lot['is_satisfactory'] : null,
                    "get_response" => isset($lot['get_response']) ? $lot['get_response'] : 0,
                    "value" => isset($lot['value']) ? $lot['value'] : (isset($lot['total_price']) ? $lot['total_price'] : null), 
                    "vat" => isset($lot['vat']) ? $lot['vat'] : null,
                    "specification" => isset($lot['specification']) ? $lot['specification'] : '',
                    "total_price" => isset($lot['total']) ? $lot['total'] : null,
                    "participant_id" => isset($lot['participant_id']) ? $lot['participant_id'] : null,
                ];
            }
            ParticipantRow::insert($insertArrayLots);
        }
        $insertArray = [];
        if($type === 'full'){
            foreach($this->request->participant as $key => $request){
                $insertArray[$key] = [
                    "group_id" => $group_id,
                    "organize_id" => $this->request->organize_id,
                    "email" => $request['email'],
                    "phone" => $request['phone'],
                    "tin" => $request['tin'],
                    "is_docs_satisfactory" => $request['is_docs_satisfactory'],
                    "price_offer_exists" => $request['price_offer_exists'],
                    "date_of_submission" => $request['date_of_submission'],
                    "is_physical_person" => $request['is_physical_person'],
                    "id_card_number" => $request['id_card_number'],
                    "first_name" => json_encode($request['first_name']),
                    "last_name" => json_encode($request['last_name']),
                    "middle_name" => json_encode($request['middle_name']),
                    "name" => json_encode($request["name"]),
                    "address" => json_encode($request["address"]),
                ];
            }
        } else {
            foreach($this->request->participant as $key => $request){
                $insertArray[$key] = [
                    "group_id" => $group_id,
                    "organize_id" => $this->request->organize_id,
                    "tin" => $request['tin'],
                    "name" => json_encode($request["name"]),
                    "address" => json_encode($request["address"]),
                ];
            }
        }
        Participant::insert($insertArray);
        return $group_id;
    }

}
