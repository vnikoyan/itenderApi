<?php

namespace App\Http\Controllers\Admin\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Http\Controllers\Api\Mail\MailController;
use App\Models\User\User;
use App\Models\Order\Order;
use App\Models\Package\Package;
use App\Models\Order\OrderState;
use App\Models\Order\PaymentHistory;
use App\Models\User\Organisation;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Log;
use Auth;

class OrderController extends AbstractController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:package');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function private(){
        return view('admin.order.private');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function state(){
        return view('admin.order.state');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function paymentHistory(){
        return view('admin.order.paymentHistory');
    }
    // /**
    //  * Show the application dashboard.
    //  *
    //  * @return \Illuminate\Contracts\Support\Renderable
    //  */
    // public function create(Package $packages){
    //     return view('admin.order.order.add',compact('order'));
    // }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    // public function edit($id){
    //     $packages = Package::findOrFail($id);
    //     return view('admin.order.order.edit',compact('order'));
    // }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    // public function update(Request $request,$id){
    //     $packages = Package::findOrFail($id);
    //     $packages->price_1 = $request->price_1;
    //     $packages->price_3 = $request->price_3;
    //     $packages->price_6 = $request->price_6;
    //     $packages->price_12 = $request->price_12;
    //     $packages->save();
    //     return redirect("/admin/order");
    // }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function tableDataPrivate(Request $request){
        $tableData =  Datatables::of(Order::select("order.*",
            "order.id as orderId",
            "users.name",
            "users.name as user_name",
            "users.id as userId",
            "users_state_organisation.company_type",
            "users_state_organisation.id_card_number")
                                          ->where("order.package_id","!=", 1)
                                          ->join("users","users.id","order.user_id")
                                          ->join("users_state_organisation","users_state_organisation.id","users.parent_id")
                                          ->orderBy('strat_date', 'DESC')
                                          ->with("package")
                                        );
        return $tableData->addColumn('package_id', function ($order) {
                     return $order->package->name;
                 })->addColumn('userName', function ($order) {
                    $username = json_decode($order->user_name)->hy;
                    $findme   = $order->company_type;
                    if($order->id_card_number){
                        return $username;
                    }else{
                        return $username.' '.$order->company_type;
                    }
                 })->addColumn('payment_method', function ($order) {
                    if($order->payment_method == "arca"){
                        return 'Քարտային փոխանցում';
                    }
                    if($order->payment_method == "bank transfer"){
                        return 'Բանկային փոխանցում';
                    }
                    if($order->payment_method == "added by admin"){
                        return 'Ավելացված է ադմինի կողմից';
                    }
                    if($order->payment_method == "trial period"){
                        return 'Փորձաշրջան';
                    }
                    return  " ";
                 })->addColumn('action', function ($order) {

                        if(strtotime($order->end_date) < strtotime(date("Y-m-d H:i:s"))){
                            return '<a  href="/admin/delete/order/'.$order->orderId.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                        if($order->type == "ACTIVE"){
                            return '<a  href="/admin/pause/order/'.$order->orderId.'"class="btn btn-xs btn-warning ml-1" ><i class="fa fa-pause" data-toggle="tooltip" title="" data-original-title="Կասեցնել"></i><a  href="/admin/delete/order/'.$order->id.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                        if($order->type == "PASSIVE" && $order->payment_method != 'trial period'){
                            return '<a  href="/admin/approve/order/'.$order->orderId.'"class="btn btn-xs btn-success ml-1" ><i class="fa fa-check"></i></a><a  href="/admin/delete/order/'.$order->id.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                        if($order->type == "SUSPENDED" && strtotime($order->end_date) > strtotime(date("Y-m-d"))){
                            return '<a href="/admin/continue/order/'.$order->orderId.'"class="btn btn-xs btn-info ml-1" ><i class="fa fa-play" data-toggle="tooltip" title="" ></i></a><a  href="/admin/delete/order/'.$order->id.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                 })->addColumn('packageName', function ($order) {
                    $duration = strtotime($order->end_date) - strtotime($order->strat_date);
                    $packages = Package::get();
                    $packageName = [
                        1 => "Անվճար",
                        2 => "Էկոնոմ",
                        3 => "Պրեմիում",
                        4 => "Գոլդ",
                     ];                    
                    $endDate = date("Y-m-d",strtotime($order->end_date));
                    $startDate = date("Y-m-d",strtotime($order->strat_date));
                    $days = strtotime($endDate) - strtotime($startDate);
                    $price = 0;
                    if($order->payment_method == "trial period"){
                        $days = $days / (60 * 60 * 24);
                        return $packageName[$order->package->id].' փաթեթ '.$days.' օր';
                    }else{
                        foreach ($order->package->toArray() as $key => $value) {
                            if($value == $order->amount_paid){
                               $price = $key;
                            }
                        }
                    }
                    if($price){
                        $month = explode('price_',$price)[1];
                    } else {
                        $month = '???????';
                    }
                    return $packageName[$order->package->id].' փաթեթ '.$month.' ամիս';
                 })->addColumn('amount', function ($order) {
                     return $order->amount_paid.' դրամ';
                 })->addColumn('className', function ($order) {
                    if(strtotime($order->end_date) < strtotime(date("Y-m-d H:i:s")) ){
                        return 'red';
                    }
                    if($order->payment_method == "trial period"){
                        return 'white';
                    }
                    if($order->payment_method != "trial period" && $order->payment_aproved == 0){
                        return 'orange';
                    }
                    return 'white';
                 })->make(true);
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function tableDataState(Request $request){
        $tableData =  Datatables::of(OrderState::orderBy('strat_date','DESC')->where("order_state.deleted_at", null)->with("packageState")->with("packageUser"));
        return $tableData->addColumn('userName', function ($order) {
                    $name  =  isset($order->packageUser['name']) ? $order->packageUser['name'] : ' ';
                    $username = $name;
                    $findme   = isset($order->packageUser->company_type) ? $order->packageUser->company_type : ' ';
                    if(str_contains($name, $findme) !== false){
                        return $name;
                    }else{
                        return $name.' '.$order->packageUser->company_type;
                    }
                   })->addColumn('payment_method', function ($order) {
                    if($order->payment_method == "arca"){
                        return 'Քարտային փոխանցում';
                    }
                    if($order->payment_method == "bank transfer"){
                        return 'Բանկային փոխանցում';
                    }
                    if($order->payment_method == "added by admin"){
                        return 'Ավելացված է ադմինի կողմից';
                    }
                    if($order->payment_method == "trial period"){
                        return 'Փորձաշրջան';
                    }
                    return  " ";
                 })->addColumn('action', function ($order) {
                        if(strtotime($order->end_date) < strtotime(date("Y-m-d H:i:s"))){
                            return '<a  href="/admin/delete/order/state/'.$order->id.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                        if($order->type == "ACTIVE"){
                            return '<a  href="/admin/pause/order/state/'.$order->id.'"class="btn btn-xs btn-warning ml-1" ><i class="fa fa-pause" data-toggle="tooltip" title="" data-original-title="Կասեցնել"></i><a  href="/admin/delete/order/state/'.$order->id.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                        if($order->type == "PASSIVE" && $order->payment_method != 'trial period'){
                            return '<a  href="/admin/approve/order/state/'.$order->id.'"class="btn btn-xs btn-success ml-1" ><i class="fa fa-check"></i></a><a  href="/admin/delete/order/state/'.$order->id.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                        if($order->type == "SUSPENDED" && strtotime($order->end_date) > strtotime(date("Y-m-d"))){
                            return '<a href="/admin/continue/order/state/'.$order->id.'"class="btn btn-xs btn-info ml-1" ><i class="fa fa-play" data-toggle="tooltip" title="" ></i></a><a  href="/admin/delete/order/state/'.$order->id.'"class="btn btn-xs btn-danger ml-1"><i class="fa fa-ban"></i></a>';
                        }
                 })->addColumn('packageName', function ($order) {
                        $packageName = $order->packageState->name;
                        $endDate = date("Y-m-d",strtotime($order->end_date));
                        $startDate = date("Y-m-d",strtotime($order->strat_date));
                        $days = strtotime($endDate) - strtotime($startDate);
                        if($order->payment_method == "trial period"){
                            $days = $days / (60 * 60 * 24);
                            return $packageName.' փաթեթ '.$days.' օր';
                        }else{
                            return $packageName." փաթեթ 12 ամիս";
                        }

                    
                 })->addColumn('amount', function ($order) {
                     return $order->amount_paid.' դրամ';
                 })->addColumn('className', function ($order) {
                    if(strtotime($order->end_date) < strtotime(date("Y-m-d H:i:s")) ){
                        return 'red';
                    }
                    if($order->payment_method == "trial period"){
                        return 'white';
                    }
                    if($order->payment_method != "trial period" && $order->payment_aproved == 0){
                        return 'orange';
                    }
                    return 'white';
                 })->make(true);
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function tableDataPaymentHistory(){
        $tableData =  Datatables::of(PaymentHistory::with("user"));
            return $tableData->addColumn('user_id', function ($order) {
                        if($order->user){
                            return $order->user->name;
                        }else{
                            return $order->id;
                        }       
                    })->addColumn('action', function ($order) {
                        if($order->type == "ACTIVE"){
                            return "<span style='color: green; border: 1px solid; padding: 5px;'>Հաջողված</span>";
                        }else{
                            return "<span style='color: red; border: 1px solid; padding: 5px;'>Անհաջող</span>";
                        }       
                    })->editColumn('id', 'ID: {{$id}}')->make(true);
    }

    public function deleteOrder($id){
        Order::where('id', $id)->update(['type'=>'PASSIVE','deleted_at' => date("Y-m-d H:i:s")]);
        return redirect()->back();
    }

    public function deleteOrderState($id){
        OrderState::where('id', $id)->update(['type'=> 'PASSIVE','deleted_at' => date("Y-m-d H:i:s")]);
        return redirect()->back();
    }


    public function approveOrder($id){

        $order = Order::where('id', $id)->first();
        $dayDifference = date("d",strtotime(date("Y-m-d")) - strtotime($order->strat_date));
        $days = strtotime(date("Y-m-d H:i:s")) - strtotime($order->strat_date);
        $days = floor($days / (60 * 60 * 24));
        $newEndDate = date('Y-m-d H:i:s', strtotime($order->end_date. ' +'.$days.' days'));
        Order::where('id', $id)->update(['strat_date'=> date("Y-m-d H:i:s"),'end_date' => $newEndDate,'type' => 'ACTIVE','payment_aproved' => 1]);

        $order = Order::select("packages.name as packageName ","order.strat_date as sDate","order.strat_date as eDate","order.amount_paid", 
                        "users.email","users.id","order.payment_method","users.email_notifications")
                        ->join("users","users.id","=","order.user_id")
                        ->join("packages","packages.id","=","order.package_id")
                        ->where('order.id', $id)
                        ->first();
        $user = User::where("id",$order->id)->with('organisation')->first();
        if($user->organisation->id_card_number){
            $companyName = $user->organisation->name;
        } else {
            $companyName = '«'.$user->organisation->name.'» '.$user->organisation->company_type;
        }               
        $paymentHistory =  new PaymentHistory;
        $paymentHistory->user_id = $order->id;
        $paymentHistory->amount_paid = $order->amount_paid;
        $paymentHistory->strat_date = $order->sDate;
        $paymentHistory->payment_method = $order->payment_method;
        $paymentHistory->type = "ACTIVE";
        $paymentHistory->save();

        $startDate = date("Y-m-d",strtotime($order->sDate));
        $endDate = date("Y-m-d",strtotime($order->eDate));
        $html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, iTender համակարգի ծառայությունների փաթեթը՝ ". $order['packageName '] .", ակտիվացված է, որն ակտիվ է ".$startDate."-ից մինչև ".$endDate."-ը ներառյալ:</p></br><a href = 'https://www.youtube.com/channel/UCD3gwb1H1NE9qujwPzCd3pg/videos'> iTender համակարգից օգտվելու ուղեցույց</a></br><p>Հարգանքով՝ iTender թիմ</p>";
        $subj = "Ծառայությունների փաթեթի ձեռք բերում";
        $mailController = new MailController;
        $mailController->new_mail($order->email, $subj,$html);
        return redirect()->back();
    }

    public function approveOrderState($id){

        $order = OrderState::where('id', $id)->first();
        $dayDifference = date("d",strtotime(date("Y-m-d H:i:s")) - strtotime($order->strat_date));
        $newEndDate = date('Y-m-d H:i:s', strtotime($order->end_date. ' +'.$dayDifference.' days'));
        OrderState::where('id', $id)->update(['strat_date'=> date("Y-m-d H:i:s"),'end_date' => $newEndDate,'type' => 'ACTIVE','payment_aproved' => 1]);

        $orderState = OrderState::where("order_state.id",$id)->with("packageState")->with("packageUser")->first();
        $user = Organisation::where("id",$orderState->packageUser->id)->with('user')->first();
        if($user->organisation->id_card_number){
            $companyName = $user->organisation->name;
        } else {
            $companyName = '«'.$user->organisation->name.'» '.$user->organisation->company_type;
        }
        $user->email =  $user->user[0]->email; 
        $paymentHistory =  new PaymentHistory;
        $paymentHistory->user_id = $user->id;
        $paymentHistory->amount_paid = $orderState->packageState->price;
        $paymentHistory->strat_date = $orderState->strat_date;
        $paymentHistory->payment_method = $orderState->payment_method;
        $paymentHistory->type = "ACTIVE ";
        $paymentHistory->save();
        $packageName = $orderState->packageState->name;
        $html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, iTender համակարգի ծառայությունների փաթեթը ՝ ". $packageName .", ակտիվացված է, որն ակտիվ է ".date("Y-m-d H:i",strtotime($orderState->end_date))."-ից մինչև ".date("Y-m-d H:i", strtotime($orderState->strat_date) )."-ը ներառյալ:</p></br><a href = 'https://www.youtube.com/channel/UCD3gwb1H1NE9qujwPzCd3pg/videos'> iTender համակարգից օգտվելու ուղեցույց</a></br><p>Հարգանքով՝ iTender թիմ</p>";
        $subj = "Ծառայությունների փաթեթի ձեռք բերում";
        $mailController = new MailController;
        $mailController->new_mail($user->email, $subj, $html);
        return redirect()->back();
    }

    public function addOrder(Request $request){
        $this->validate($request, [
            'package' => 'required',
            'price' => 'required',
            'search' => 'required',
            'search-key' => 'required',
            'month' => 'required',
        ]);
        $today = date("Y-m-d H:i:s");
        $endDate = date('Y-m-d H:i:s',strtotime($today. ' + '.(int) $request->input('month').' months'));
        $packages = Package::get();
        $user_id = $request->input("search-key");
        $order = new Order;
        $order->user_id = $user_id;
        $order->package_id = $request->input("package");
        $order->strat_date = date("Y-m-d H:i:s");
        $order->end_date = $endDate;
        $order->amount_paid = $request->input("price");
        $order->type = "ACTIVE";
        $order->deleted_at = NULL;
        $order->save();

        return redirect()->back()->with('success', 'Փաթեթը հաջողությամբ ավելացված է');  
    }

    public function pauseOrder($id){
        Order::where('id', $id)->update(['type' => 'SUSPENDED']);
        return redirect()->back();   
    }

    public function pauseOrderState($id){
        OrderState::where('id', $id)->update(['type' => 'SUSPENDED']);
        return redirect()->back(); 
    }


    public function continueOrder($id){
        Order::where('id', $id)->update(['type' => 'ACTIVE']);
        return redirect()->back();   
    }

    public function continueOrderState($id){
        OrderState::where('id', $id)->update(['type' => 'ACTIVE']);
        return redirect()->back();   
    }
}
