<?php


namespace App\Services\Contract;

use App\Models\Contract\ContractClient;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Contract\Contracts;
use App\Models\Contract\ContractLots;
use App\Jobs\ProcessNewTenderAdded;
use App\Models\Organize\OrganizeRow;
use App\Notifications\TenderCreated;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;

use Exception;

class ContractsService
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

    public function createContract(){
        $contract = new Contracts();
        $contractData = $this->builder($contract);
        $user = auth('api')->user();

        if(!$contractData->is_sign){
            $user = User::find($contractData->provider_user_id);
            $data = new \stdClass();
            // $data->email = trim($user->email);
            $data->email = 'hikespammail@gmail.com';
            $password = $contractData->code;
            if( $password != null){
                $data->subject = "Նոր պայմանագրի առաջարկ: Պայմանագրի համար՝ $password";
            }
            $organisation = auth('api')->user()->organisation;
            $customer = $organisation->id_card_number ? $organisation->name : ('«'.$organisation->name.'» '.$organisation->company_type);
            $url = \Config::get('values')['frontend_url'].'/participant/contract-management/requests';
            $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                            Պայմանագրի անվանում` ".htmlentities($contractData->name)."
                            </div>
                            <div style='display: none; max-height: 0px; overflow: hidden;'>
                            &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                            </div>
                            <p>Պատվիրատուն՝ ".htmlentities($customer)."</p><br>
                            <p>Պայմանագրի անվանում՝ ".htmlentities($contractData->name)."</p></br>
                            <p>Պայմանագրի համար՝ ".$password."</p></br>
                            <p>Կնքման ամսաթիվ՝ ".date("Y-m-d H:i",strtotime($contractData->sign_date))."</p>"; 
                            $data->text.= " <a href = '".$url."'>Տեսնել</a></br><p>Հարգանքով՝ iTender թիմ</p>";
                            
            if($user->email_notifications){
                ProcessNewTenderAdded::dispatch($data);
            }
            if($user->telegram_notifications){
                $subject = substr($data->subject, 0, strpos($data->subject, ":"));
                $content = "*$subject*
    
Պատվիրատուն՝ *".$customer."*
Պայմանագրի անվանում՝ *".$contractData->name."*
Պայմանագրի համար՝ *".$password."*
Կնքման ամսաթիվ՝ *".date("Y-m-d H:i",strtotime($contractData->sign_date))."*
                ";
                Notification::send($user, new SendNotification($user->id, $content, $url));
            }
            $notification_data = [
                'customer' => $customer,
                'type' => 'contract_request',
                'subject' => $data->subject,
                'tender_id' => 0
            ];
            // $user->notify(new TenderCreated($notification_data));
        }
        return $contractData;
    }

    public function fromApplication(){
        $contract = new Contracts();
        $data = $this->request->all();
        if($data['client']){
            $client = new ContractClient();
            $client->account_number = $data['client']['account_number'];
            $client->bank = $data['client']['bank'];
            $client->name = $data['client']['name'];
            $client->tin = $data['client']['tin'];
            $client->save();
        }
        $contract->contract_client_id = $client->id;
        return $this->builder($contract);
    }

    public function fromApplicationComplete(){
        $data = $this->request->all();
    
        $contract = Contracts::findOrFail($data['id']);
        $contract->code = $data['code'];
        $contract->name = $data['name'];
        $contract->price = $data['price'];
        $contract->sign_date = $data['sign_date'];
        $contract->save();

        $client = ContractClient::findOrFail($contract->contract_client_id);
        $client->address = $data['client_address'];
        $client->save();

        ContractLots::where('contract_id', $contract->id)->delete();
        $insertArrayLots = [];
        foreach ($data['rows'] as $key => $lot) {
            $insertArrayLots[$key] = [
                "contract_id" => $contract->id,
                "total_price" => $lot['total_price'], 
                "price_unit" => $lot['unit_price'], 
                "available" => $lot['count'],
                "specification" => $lot['specification'],
                "name" => $lot['name'],
                "delivery_date" => $lot['delivery_date'],
                "payment_date" => $lot['payment_date'],
                "unit" => $lot['unit'],
                "view_id" => $lot['number'],
            ];
        }
        ContractLots::insert($insertArrayLots);

        return $contract;
    }
    
    public function updateContract($id):Contracts{
        $contract = Contracts::findOrFail($id);
        return $this->builder($contract);
    }

    private function builder(Contracts $contract) {
        $data = $this->request->all();
        foreach ($data as $key => $value){
            try {
                if($key !== 'lots' && $key !== 'client'){
                    $contract->{$key} = $value;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        $contract->save();
        if(isset($data['lots'])){
            ContractLots::where('contract_id', $contract->id)->delete();
            $insertArrayLots = [];
            foreach ($data['lots'] as $key =>  $lot) {
                $insertArrayLots[$key] = [
                    "contract_id" => $contract->id,
                    "organize_row_id" => $lot['id'],
                    "total_price" => $lot['price'], 
                    "price_unit" => $lot['price_unit'], 
                    "available" => $lot['count'],
                ];
            }
            ContractLots::insert($insertArrayLots);
        }
        return $contract;

    }

}
