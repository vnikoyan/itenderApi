<?php

namespace App\Http\Controllers\Api\Settings;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Models\Package\Package;
use App\Models\Package\PackageState;
use App\Models\Order\Order;
use App\Models\Order\OrderState;
use App\Models\Settings\VtbReport;
use App\Http\Controllers\Api\Mail\MailController;
use App\Models\Order\PaymentHistory;
use App\Http\Controllers\Api\AbstractController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class UserPaymentController extends AbstractController
{
    

    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function createOrderPaymentUrl(Request $request){
        
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'package_id' => ['required', 'integer'],
            'month' => ['required'],
            'method' => ['required'],
        ]);
        
        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }
        
        $package = Package::where("id",$request->input("package_id"))->first();
        if(trim($request->input("method")) == "arca" && !is_null($package)){
            $price = "price_".$request->input("month");
            $paymentHistory = new PaymentHistory;
            $paymentHistory->user_id = auth('api')->user()->id;
            $paymentHistory->amount_paid = $package->$price;
            $paymentHistory->strat_date = date("Y-m-d H:i:s");
            $paymentHistory->payment_method = $request->input("method");
            $paymentHistory->type = "NULL";
            $paymentHistory->save();
            $order = new Order;
            $order->user_id = auth('api')->user()->id;
            $order->package_id = $request->package_id;
            $order->package_id = $request->package_id;
            $order->strat_date = date("Y-m-d H:i:s");
            $order->end_date = date("Y-m-d H:i:s", strtotime('+'.$request->input("month").'months'));
            $order->payment_method = $request->input("method");
            $order->amount_paid = $package->$price;
            $order->type = "PASSIVE";
            $order->save();
            $user = auth('api')->user();
            if($user->organisation->tin){
                $label = "  Tin: ";
                $numbers = $user->organisation->tin;
            }else{
                $label = "  Passport serial number: ";
                $numbers = $user->organisation->passport_serial_number;
            }
            $description = $label.$numbers;
            // $description = mb_convert_encoding($description, 'UTF-8', 'UCS-2BE');
            $id = strtotime(date("Y-m-d H:i:s"));
            // $endpoint = 'https://ipaytest.arca.am:8445/payment/rest/register.do';
            $endpoint = 'https://ipay.arca.am:443/payment/rest/register.do';
            $ch = curl_init();
            $params = array(
                // 'userName' => 'itender.am_api',
                // 'password' => 'e1275400',
                'userName' => '34538967_api',
                'password' => '$Itender1$',
                'orderNumber' => 'order_'.$id,
                'amount' => (100 * (int) $package->$price),
                'description' => $description,
                'returnUrl' => 'https://api.itender.am/payment/',
                // 'returnUrl' => 'http://localhost:8000/payment/',
                'jsonParams' => '{"FORCE_3DS2":"true"}',
                'language' => 'hy',
                'currency' => '051',
            );
            $pageURL = $endpoint . '?' . http_build_query($params);
    
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $pageURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
            $page = curl_exec($ch);
            $order->arca_order_id = json_decode($page)->orderId;
            $order->save();
            curl_close($ch);
            return json_decode($page,true);
        }

    }


    public function createOrderStatePaymentUrl(Request $request){

        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'package_id' => ['required', 'integer'],
            'method' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }
        
        $package = PackageState::where("id",$request->input("package_id"))->first();
        $price = $package->price;
        $paymentHistory = new PaymentHistory;
        $paymentHistory->user_id = auth('api')->user()->id;
        $paymentHistory->amount_paid = $price;
        $paymentHistory->strat_date = date("Y-m-d H:i:s");
        $paymentHistory->payment_method = $request->input("method");
        $paymentHistory->type = "NULL";
        $paymentHistory->save();

        $orderState = new OrderState;
        $orderState->organisation_id = auth('api')->user()->parent_id;
        $orderState->package_id = $package->id;
        $orderState->strat_date = date("Y-m-d H:i:s");
        $orderState->end_date = date("Y-m-d H:i:s", strtotime('+1 years'));
        $orderState->payment_method = $request->input("method");
        $orderState->amount_paid = $price;
        $orderState->type = "PASSIVE";
        $orderState->save();
        $pName = $package->name;
        $url = \Config::get('values')['frontend_url'];
        $subject = "Ծառայությունների փաթեթի ձեռք բերում";
        $html = "<p>Հարգելի գործընկեր, Դուք ցանկանում եք ձեռք բերել iTender համակարգի ծառայությունների փաթեթ՝ ".$pName. ", որի գինը կազմում է ".$price." դրամ Ծառայություններն ակտիվացնելու համար, խնդրում ենք 1 աշխատանքային օրվա ընթացքում կատարել վճարումը։ Վճարման տարբերաներին կարող եք ծանոթանալ <a href = '".$url.'/packages'."'>այստեղ</a></p></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
        $mailController = new MailController;
        $mailController->new_mail(auth('api')->user()->email,$subject,$html);
        $ogName = auth('api')->user()->organisation->name;
        $companyType = auth('api')->user()->organisation->company_type;
        $description = " <<".$ogName.">> ".$companyType." ".$pName." ".$request->input("month")." ամիս";
        $id = strtotime(date("Y-m-d H:i:s"));
        if($request->input("method") === "arca"){
            $pageURL = 'https://ipay.arca.am/payment/rest/register.do?userName=34538967_api&password=$Itender1$&language=hy&orderNumber=' . $id . '&amount=' .(100 * (int) $price). '&currency=051&description=iTender N' . $id . $description .'&returnUrl=https://www.itender.am/acba/';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_URL, $pageURL);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
            $page = curl_exec($ch);
            curl_close($ch);
            return json_decode($page,true);
        }
    }

    public function goldPackageProbationActivate(){

        $user = auth('api')->user();
        $packageId = 4;
        $order = new Order;
        $order->user_id = $user->id;
        $order->package_id = $packageId;
        $order->strat_date = date("Y-m-d H:i:s");
        $order->end_date = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 10 days'));
        $order->amount_paid = 0;
        $order->payment_method = 'trial period';
        $order->type = "ACTIVE";
        $order->save();
        $html = "<p>Հարգելի գործընկեր, Դուք ակտիվացրել եք 10 օր iTender համակարգից անվճար օգտվելու փորձաշրջան, որի ընթացքում կարող եք օգտվել համակարգի բոլոր հնարավորություններից։   Փորձաշրջանի ժամկետն ավարտվում է ".date('Y-m-d H:i', strtotime(date("Y-m-d H:i").' + 10 days'))."-ին:</p></br>
            <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";     
        $subject = "ԱՆՎՃԱՐ փորձաշրջանի ակտիվացում";
        $mailController = new MailController;
        $mailController->new_mail($user->email,$subject,$html);
        $user->probation = 1;
        $user->save();
    }

    public function createOrderBankTransfer(Request $request){
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'package_id' => ['required', 'integer'],
            'month' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }
        
        $package = Package::where("id",$request->input("package_id"))->first();
        $price = "price_".$request->input("month");
        $paymentHistory = new PaymentHistory;
        $paymentHistory->user_id = auth('api')->user()->id;
        $paymentHistory->amount_paid = $package->$price;
        $paymentHistory->strat_date = date("Y-m-d H:i:s");
        $paymentHistory->payment_method = 'Bank transfer';
        $paymentHistory->type = "NULL";
        $paymentHistory->save();

        $orderState = new Order;
        $orderState->user_id = auth('api')->user()->id;
        $orderState->package_id = $package->id;
        $orderState->strat_date = date("Y-m-d H:i:s");
        $orderState->end_date = date("Y-m-d H:i:s", strtotime('+'.$request->input("month").'months'));
        $orderState->payment_method = 'bank transfer';
        $orderState->amount_paid = $package->$price;
        $orderState->type = "PASSIVE";
        $orderState->save();
        $pName = $package->name;
        $url = \Config::get('values')['frontend_url'];
        $subject = "Ծառայությունների փաթեթի ձեռք բերում";
        $html = "<p>Հարգելի գործընկեր, Դուք ցանկանում եք ձեռք բերել iTender համակարգի ծառայությունների փաթեթ՝ ".$pName. ", ".$request->input("month") ." ամսով, որի գինը կազմում է ".$package->$price." դրամ Ծառայություններն ակտիվացնելու համար, խնդրում ենք 1 աշխատանքային օրվա ընթացքում կատարել վճարումը։ Վճարման տարբերաներին կարող եք ծանոթանալ <a href = '".$url.'/packages'."'>այստեղ</a></p></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
        $mailController = new MailController;
        $mailController->new_mail(auth('api')->user()->email,$subject,$html);
        
        return  response()->json(['error' => true, 'false' => 'package added']);
    }

    public function goldPackageProbationActivateOrderState(){
        $user = auth('api')->user();
        $package = PackageState::where("name","Գոլդ")->first();
        $order = new OrderState;
        $order->organisation_id = auth('api')->user()->parent_id;
        $order->package_id = $package->id;
        $order->strat_date = date("Y-m-d H:i:s");
        $order->end_date = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 10 days'));
        $order->amount_paid = 0;
        $order->payment_method = 'trial period';
        $order->type = "ACTIVE";
        $order->save();
        $html = "<p>Հարգելի գործընկեր, Դուք ակտիվացրել եք 10 օր iTender համակարգից անվճար օգտվելու փորձաշրջան, որի ընթացքում կարող եք օգտվել համակարգի բոլոր հնարավորություններից։   Փորձաշրջանի ժամկետն ավարտվում է ".date('Y-m-d H:i', strtotime(date("Y-m-d H:i").' + 10 days'))."-ին:</p></br>
            <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";     
        $subject = "ԱՆՎՃԱՐ փորձաշրջանի ակտիվացում";
        $mailController = new MailController;
        $mailController->new_mail($user->email, $subject,$html);
        $user->probation = 1;
        $user->save();
    }

    public function bannerClick(){

        $user = auth('api')->user();
        $VtbReport = new VtbReport;
        $VtbReport->user_id = $user->id;
        $VtbReport->action = "banner click";
        $VtbReport->save();

        return response()->json(['error' => false, 'message' => 'banner successfully added']);

    }

    public function getPayment(Request $request){
        $order_id = $request->get('orderId');
        // $endpoint = 'https://ipaytest.arca.am:8444/payment/rest/getOrderStatusExtended.do';
        $endpoint = 'https://ipay.arca.am:443/payment/rest/getOrderStatusExtended.do';
        $ch = curl_init();
        $params = array(
            'userName' => '34538967_api',
            'password' => '$Itender1$',
            // 'userName' => 'itender.am_api',
            // 'password' => 'e1275400',
            'orderId' => $order_id,
        );
        $pageURL = $endpoint . '?' . http_build_query($params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $pageURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $response = curl_exec($ch);
        $parsed_response = json_decode($response);
        $url = Config::get('values')['frontend_url'];
        if($parsed_response->actionCode === 0){
            $order = Order::where('arca_order_id', $order_id)->first();
            app('App\Http\Controllers\Admin\Order\OrderController')->approveOrder($order->id);
            return redirect()->to($url.'?order=done');
        } else {
            $order = Order::where('arca_order_id', $order_id)->first();
            app('App\Http\Controllers\Admin\Order\OrderController')->deleteOrder($order->id);
            return redirect()->to($url.'?order=cancel');
        }
    }
}
