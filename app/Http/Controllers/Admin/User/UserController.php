<?php

namespace App\Http\Controllers\Admin\User;

use Exception;
use App\Http\Controllers\Admin\AbstractController;
use App\Http\Controllers\Api\Mail\MailController;
use App\Models\User\User;
use App\Models\Package\Package;
use App\Models\Order\Order;
use App\Models\Cpv\Cpv;
use App\Models\User\Organisation;
use App\Models\UserCategories\UserCpvs;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\User\User\UserStoreRequest;
use App\Http\Requests\User\User\UserUpdateRequest;
use App\Services\User\User\UserService;
use Yajra\Datatables\Datatables;
use Auth;

class UserController extends AbstractController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function __construct(){
        $this->middleware('permission:user');
    }

    /**
     * Show the application dashboard.
     *
     * @param string $type
     * @return Renderable
     */
    public function index($type = "all"){   
        return view('admin.user.index',compact('type'));
    }

    /**
     * Show the application dashboard.
     *
     * @param User $users
     * @return Renderable
     */
    public function create(User $users){
        $package = Package::pluck("name",'id')->toArray();
        return view('admin.user.add',compact('users','package'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     * @param User $user
     * @return Response
     */
    public function store(UserStoreRequest $request,User $user){
        $service = new UserService($request);
        $service->createUser($user);
        return redirect("/admin/user");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id){
        $order = Order::select('order.id','users.id as userId','users.status','users.balans','users.name','users.email','users.tin','users.phone', 'packages.name as packageName','order.strat_date','order.end_date','order.package_id')->join("users","users.id","=","order.user_id")->join("packages","packages.id","=","order.package_id")->where("order.id",$id)->first();
        $package = Package::pluck("name",'id')->toArray();
        return view('admin.user.edit',compact('order','package'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id){
        $user = User::findOrFail($id);
        return view('admin.user.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function getPermission(int $id){
        $user = User::findOrFail($id);
        $permission = Permission::get()->groupBy('code');
        return view('admin.user.permission',compact('user','permission','adminPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserUpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update( UserUpdateRequest $request, int $id ){

        $month      = $request->month;
        $package_id = $request->package_id;
        $orderId    = $request->input('orderId');
        $package = Package::where('id',$package_id)->first();
        $pc = "price_".$month;
        $order = Order::where("id",$orderId)->first();
        if(is_null($month)){
            $endDate = $request->endDate;
            if(!is_null($order)){
                $packagePrice = $order->amount_paid;
            }
        }else{
            $packagePrice = $package->$pc;
            $pName = $package->name; 
            $packagePrice = $package->$pc;
            $endDate = date('Y-m-d H:i:s', strtotime($request->startDate.'+'.$month.' months'));
        }
        $startDate  = $request->startDate;
        $trial_package_id = $request->trial_package_id; 
        $trial_startDate = $request->trial_startDate; 
        $trial_endDate = $request->trial_endDate; 
        $trial_order_id = $request->trial_order_id; 
        $usgId = $request->user_state_org;
        $id_card_number = $request->id_card_number;
        $passport_serial_number = $request->passport_serial_number;
        $request->request->add(['company_name' => $request->name]);
        $request->request->remove('passport_serial_number');
        $request->request->remove('user_state_org');
        $request->request->remove('id_card_number');
        $request->request->remove('startDate');
        $request->request->remove('month');
        $request->request->remove('endDate');
        $request->request->remove('orderId');
        $request->request->remove('orderId');
        $request->request->remove('package_id');
        $request->request->remove('trial_endDate');
        $request->request->remove('trial_startDate');
        $request->request->remove('trial_package_id');
        $request->request->remove('trial_order_id');
        
        $service = new UserService($request);
        $service->updateUser($id);
        
        $mailController = new MailController;
        $userEmail = User::where("id",$id)->with('organisation')->first();
        if($userEmail->organisation->id_card_number){
            $companyName = $userEmail->organisation->name;
        } else {
            $companyName = '«'.$userEmail->organisation->name.'» '.$userEmail->organisation->company_type;
        }  
        $subject = "Փաթեթի ակտիվացում";
        $pName = $package->name; 
        
        if( !is_null($request->password) && $request->password === $request->password_confirmation){
            User::where('id', $id)
            ->update(['password' => bcrypt($request->password)]);
        }
        
        if( !is_null($usgId) ){
            Organisation::where('id',$usgId)->update(['id_card_number' => $id_card_number, 'passport_serial_number' => $passport_serial_number ]);
        }
        
        if( $request->status == "BLOCK" ){
            $subject = "Փաթեթի ապաակտիվացում";
            $userOrders  = Order::where('user_id',$id)->where('package_id',"!=","1")->where("type","!=","trial period")->count();
            Order::where('user_id',$id)->where('package_id',"!=","1")->where("type","!=","trial period")->delete();
            $html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, iTender համակարգի ծառայությունների փաթեթը ապաակտիվացված է։</p></br><p>Հարգանքով՝ iTender թիմ</p>";
            $mailController->new_mail($userEmail->email,$subject,$html);
            return redirect("/admin/user");
        }
        if( $orderId != 0 ){
                Order::where('user_id',$id)->where('package_id',"!=","1")->where("type","!=","trial period")->delete();
                $order = new Order;
                $order->user_id = $id;
                $order->package_id = $package_id;
                $order->strat_date = date("Y-m-d H:i:s", strtotime($startDate));
                $order->end_date = $endDate;
                $order->amount_paid = $packagePrice;
                $order->payment_method = 'added by admin';
                $order->type = "ACTIVE";
                $order->payment_aproved = 1;
                $order->save();
        }else{
            if(!empty($startDate) && !empty($month)){
                $pc = "price_".$month;
                $order = new Order;
                $order->user_id = $id;
                $order->package_id = $package_id;
                $order->strat_date = date("Y-m-d H:i:s", strtotime($startDate));
                $order->end_date = $endDate;
                $order->amount_paid = $package->$pc;
                $order->payment_method = 'added by admin';
                $order->type = "ACTIVE";
                $order->payment_aproved = 1;
                $order->save();

                $html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, iTender համակարգի ծառայությունների փաթեթը ՝ ".$pName.", ակտիվացված է, որն ակտիվ է ".$startDate."-ից ".date("Y-m-d", strtotime($endDate))."-ը ներառյալ։</p></br>
                <p>iTender համակարգից օգտվելու <a href = 'https://www.youtube.com/channel/UCD3gwb1H1NE9qujwPzCd3pg/videos'> ուղեցույց </a></p></br>
                <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";

                $mailController->new_mail($userEmail->email,$subject,$html);
            }
        }
        if(!empty($trial_package_id) && !empty($trial_startDate) && !empty($trial_endDate)){
            $trial_package = Package::where('id',$trial_package_id)->first();
             $pName = $trial_package->name; 
            if($trial_order_id != 0){
                $trial_order = Order::where("id",$trial_order_id)->first();
                if(!is_null($trial_order)){
                    if($trial_order->package_id == $trial_package_id){
                        Order::where('id', $trial_order_id)->update(['strat_date' => $trial_startDate, 'end_date' => $trial_endDate]); 
                    }else{
                        // Order::where('id', $trial_order_id)->update(['type' => 'SUSPENDED' ]); 
                        $existOrderOld = Order::where('user_id', $id)->where('payment_method','trial period')->where('package_id',$trial_package_id)->first();
                        if(!empty($existOrderOld)){ 
                            Order::where('id', $existOrderOld->id)->update(['strat_date' => $trial_startDate, 'end_date' => $trial_endDate,'type'=> 'ACTIVE']); 
                            if(strtotime($existOrderOld->strat_date) != strtotime($trial_startDate) || strtotime($existOrderOld->$trial_endDate) != strtotime($endDate)){
                                $subject = "ԱՆՎՃԱՐ փորձաշրջանի ակտիվացում";
                                $html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, Դուք ակտիվացրել եք iTender համակարգից անվճար օգտվելու փորձաշրջան, որի ընթացքում կարող եք օգտվել ".$pName." փաթեթի բոլոր հնարավորություններից։ Փորձաշրջանի ժամկետն ավարտվում է ".date('Y-m-d', strtotime($trial_endDate))."-ին:</p></br>
                                <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";     
                                $mailController = new MailController;
                                $mailController->new_mail($userEmail->email,$subject,$html);
                            }                       
                        }else{
                            $order = new Order;
                            $order->user_id = $id;
                            $order->package_id = $trial_package_id;
                            $order->strat_date = date("Y-m-d H:i:s", strtotime($trial_startDate));
                            $order->end_date = date("Y-m-d H:i:s", strtotime($trial_endDate));
                            $order->amount_paid = '0';
                            $order->payment_method = 'trial period';
                            $order->type = "ACTIVE";
                            $order->save();
                            $subject = "ԱՆՎՃԱՐ փորձաշրջանի ակտիվացում";
                            $html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, Դուք ակտիվացրել եք iTender համակարգից անվճար օգտվելու փորձաշրջան, որի ընթացքում կարող եք օգտվել ".$pName." փաթեթի բոլոր հնարավորություններից։ Փորձաշրջանի ժամկետն ավարտվում է ".date('Y-m-d', strtotime($trial_endDate))."-ին:</p></br>
                            <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";     
                            $mailController = new MailController;
                            $mailController->new_mail($userEmail->email,$subject,$html);
                        } 
                    }
            }
            }else{
                // Order::where('user_id', $id)->where('package_id','!=',1)->update(['type' => 'SUSPENDED']);
                $order = new Order;
                $order->user_id = $id;
                $order->package_id = $trial_package_id;
                $order->strat_date = date("Y-m-d H:i:s", strtotime($trial_startDate));
                $order->end_date = date("Y-m-d H:i:s", strtotime($trial_endDate));
                $order->amount_paid = '0';
                $order->payment_method = 'trial period';
                $order->type = "ACTIVE";
                $order->save();
                $subject = "ԱՆՎՃԱՐ փորձաշրջանի ակտիվացում";
                $html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, Դուք ակտիվացրել եք iTender համակարգից անվճար օգտվելու փորձաշրջան, որի ընթացքում կարող եք օգտվել ".$pName." փաթեթի բոլոր հնարավորություններից։ Փորձաշրջանի ժամկետն ավարտվում է ".date('Y-m-d', strtotime($trial_endDate))."-ին:</p></br>
                <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";     
                $mailController = new MailController;
                $mailController->new_mail($userEmail->email,$subject,$html);
            }
        }
        return redirect("/admin/user");
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */

    public function getCat(Request $request){
        $data = array();
        $userId =  $request->input('id');

        $data['categories'] = Cpv::select("user_cpvs.*","users.*","cpv.*","cpv.name as cpvName")
                                 ->join("user_cpvs","user_cpvs.cpv_id","=","cpv.id")
                                 ->join("users","users.id","=","user_cpvs.user_id")
                                 ->where("users.id",$userId)
                                 ->get();
        $data['filters'] = User::select("user_filters.*")->join("user_filters","user_filters.user_id","=","users.id")->where("user_filters.user_id",$userId)->first();

        return response()->json($data);
    }

    public function destroy(int $id){
        User::where("id",$id)->delete();
        return redirect()->back();
    }

    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function tableData($data, Request $request){
        $notLoggedInUsers = $request->input('notLoggedInUsers') === 'true';
        
        if($notLoggedInUsers){
            $users = User::select("users.*","users.tin as userTin","users.name as userName","users_state_organisation.name as ogName","users_state_organisation.*","users_state_organisation.id as ogId","users.id as userId")
            ->where("users.type","USER")
            ->join("users_state_organisation","users_state_organisation.id","=","users.parent_id")
            ->where('first_login', 1)
            ->orderBy('users.created_at','DESC')->get();
        } else {
            $users = User::select("users.*","users.tin as userTin","users.name as userName","users_state_organisation.name as ogName","users_state_organisation.*","users_state_organisation.id as ogId","users.id as userId")
            ->where("users.type","USER")
            ->join("users_state_organisation","users_state_organisation.id","=","users.parent_id")
            ->orderBy('users.created_at','DESC')->get();
        }
        foreach($users as $val){
            $order = Order::where("user_id",$val->userId)
                          ->where('type','ACTIVE')
                          ->where("package_id","!=",1)
                          ->where("payment_method","!=","trial period")
                          ->whereDate("end_date",">=",date("Y-m-d"))
                          ->orderBy('end_date','DESC')->first();
            if(!is_null($order)){
                $orderId = $order->id;
                $val->orderId = $order->id;
                $val->packageName = Package::where('id',$order->package_id)->select('name')->first()->name;
            }else{
                $orderId = 0;
                $val->packageName = "Անվճար";
            }
            $val->action = '<a href="/admin/user/edit/'.$val->userId.'/'.$orderId.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Խմբագրել</a> <a href="/admin/user/delete/'.$val->userId.'" class="btn btn-xs btn-danger waves-effect waves-light "><i class="fa fa-trash"></i> Ջնջել</a>';
            if($val->status == "ACTIVE"){
                $val->status = 'Ակտիվ';
            }            
            if($val->status == "BLOCK"){
                $val->status = 'Արգելափակված';
            }
            if(is_null($val->email_verified_at)){
                $val->status = 'Չհաստատված';
            }
            $username = json_decode($val->userName)->hy;

            $findme   = $val->tin;
            if(str_contains($username, $findme) !== false){
                $val->name =  $username;
            }else{
                $val->name = $username.' '.$val->company_type;
            }
        }

       return  Datatables::of($users)->make(true);
    }

    public function getUserWithOrder($id,$orderId){
        $user = User::select("users.*","users_state_organisation.id_card_number","users_state_organisation.passport_serial_number","users_state_organisation.id as usgId")
        ->where('users.id',$id)
        ->join("users_state_organisation","users_state_organisation.id","=","users.parent_id")
        ->first();
        $order = Order::select('order.id','users.id as userId','users.status','users.balans','users.name','users.email','users.tin','users.phone', 'packages.name as packageName','order.strat_date','order.end_date','order.package_id')
        ->leftJoin("users","users.id","=","order.user_id")
        ->leftJoin("packages","packages.id","=","order.package_id")
        ->where("order.id",$orderId)
        ->where("order.payment_method","!=","trial period")
        ->whereDate("order.end_date",">=",date("Y-m-d"))
        ->first();
        $package = Package::pluck("name",'id')->toArray();
        $prices = Package::orderBy('id','ASC')->get();
        $trial_order = Order::where("type","ACTIVE")->where("payment_method","trial period")->whereDate("end_date",">=",date("Y-m-d"))->where("user_id",$id)->first();
        return view('admin.user.edit',compact('order','package','user','prices','trial_order'));
    }

    public function deleteUserWithOrder($userId, $orderId){

        User::findOrFail($userId)->delete();
        if($orderId != 0){
            Order::findOrFail($orderId)->delete();
        }
        return response()->json(['status' => true]);
    }

    public function searchUserByTin(Request $request){
        $data = User::where('tin','LIKE','%'.$request->input('tin').'%')->get();
        return response()->json($data);
        
    }
}
