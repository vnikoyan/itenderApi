<?php


namespace App\Services\Contract;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\Contract\ContractLots;
use App\Models\Contract\ContractOrders;
use App\Models\Contract\ContractOrderLots;
use App\Jobs\ProcessNewTenderAdded;
use App\Models\User\User;
use App\Models\Contract\Contracts;
use App\Notifications\TenderCreated;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use Exception;

class ContractOrdersService
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

    public function createContractOrder(){
        $contract_order = new ContractOrders();
        $contractOrderData = $this->builder($contract_order);
        $contractData = Contracts::find($contractOrderData->contract_id);
        $user = User::find($contractData->provider_user_id);
        $data = new \stdClass();
        return $contractData;
        $data->email = trim($user->email);
        // $data->email = 'hikespammail@gmail.com';
        $password = $contractData->code;
        if( $password != null){
            $data->subject = "Նոր պատվեր: Պայմանագրի Համար՝ $password";
        }
        $organisation = auth('api')->user()->organisation;
        $customer = $organisation->id_card_number ? $organisation->name : ('«'.$organisation->name.'» '.$organisation->company_type);
        $url = \Config::get('values')['frontend_url'].'/participant/contract-management/orders/active';
        $data->text = "<div style='display: none; max-height: 0px; overflow: hidden;'>
                        Պայմանագրի անվանում` ".htmlentities($contractData->name)."
                        </div>
                        <div style='display: none; max-height: 0px; overflow: hidden;'>
                        &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
                        </div>
                        <p>Պատվիրատուն՝ ".htmlentities($customer)."</p><br>
                        <p>Պայմանագրի անվանում՝ ".htmlentities($contractData->name)."</p></br>
                        <p>Պայմանագրի համար՝ ".$password."</p></br>
                        <p>Մատակարարման վերջնաժամկետ՝ ".$contractOrderData->dispatch_date."</p>"; 
                        $data->text.= " <a href = '".$url."'>Տեսնել</a></br><p>Հարգանքով՝ iTender թիմ</p>";
                        
        if($user->email_notifications){
            ProcessNewTenderAdded::dispatch($data);
        }
        if($user->telegram_notifications){
            
            $user = User::find($contractData->provider_user_id);
            $subject = substr($data->subject, 0, strpos($data->subject, ":"));
            $content = "*$subject*
        
Պատվիրատուն՝ *".$customer."*
Պայմանագրի անվանում՝ *".$contractData->name."*
Պայմանագրի համար՝ *".$password."*
Մատակարարման վերջնաժամկետ՝ *".$contractOrderData->dispatch_date."*
                    ";
            Notification::send($user, new SendNotification($user->id, $content, $url));
        }
        // $notification_data = [
        //     'customer' => $customer,
        //     'type' => 'contract_order',
        //     'subject' => $data->subject,
        //     'tender_id' => 0
        // ];
        // $user->notify(new TenderCreated($notification_data));
        return $contractOrderData;
    }

    public function updateContractOrder($id):ContractOrders{
        $contract_order = ContractOrders::findOrFail($id);
        $this->checkStatusChange($contract_order);
        return $this->builder($contract_order);
    }

    public function cancelContractOrder($id){
        $contract_order = ContractOrders::findOrFail($id);
        foreach ($contract_order->lots as $lot) {
            $contract_lot = ContractLots::find($lot['contract_lot_id']);
            $contract_lot->available = $contract_lot->available + $lot['ordered'];
            $contract_lot->supplied = $contract_lot->supplied - $lot['ordered'];
            $contract_lot->save();
        }
        $contract_order->delete();
    }

    private function checkStatusChange($contract_order):void{
        $data = $this->request->all();
        foreach ($contract_order->lots as $lot) {
            if(isset($data['status']) && $data['status'] === 'completed'){
                $contract_lot = ContractLots::find($lot['contract_lot_id']);
                $contract_lot->ordered = $contract_lot->ordered - $lot['ordered'];
                $contract_lot->supplied = $contract_lot->supplied + $lot['ordered'];
                $contract_lot->save();
            }
        }
    }

    private function builder(ContractOrders $contract_order):ContractOrders{
        $data = $this->request->all();
        foreach ($data as $key => $value){
            try {
                if($key !== 'lots'){
                    $contract_order->{$key} = $value;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        $contract_order->save();
        if(isset($data['lots'])){
            ContractOrderLots::where('contract_order_id', $contract_order->id)->delete();
            $insertArrayLots = [];
            foreach ($data['lots'] as $key => $lot) {
                $contract_lot = ContractLots::find($lot['contract_lot_id']);
                $contract_lot->available = $contract_lot->available - $lot['ordered'];
                $contract_lot->ordered = $contract_lot->ordered + $lot['ordered'];
                $contract_lot->save();
    
                $insertArrayLots[$key] = [
                    "contract_order_id" => $contract_order->id,
                    "contract_lot_id" => $lot['contract_lot_id'],
                    "ordered" => $lot['ordered'], 
                ];
            }
            ContractOrderLots::insert($insertArrayLots);
        }
        return $contract_order;

    }

}
