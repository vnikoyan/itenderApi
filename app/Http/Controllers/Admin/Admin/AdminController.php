<?php

namespace App\Http\Controllers\Admin\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\User\User;
use App\Models\User\Organisation;
use App\Models\Admin;
use App\Models\Categories\Categories;
use App\Models\UserCategories\UserCategories;
use App\Models\Settings\EmailParticipant;
use App\Models\Tender\Organizator;
use App\Models\Cpv\Cpv;
use App\Models\Tender\TenderState;
use App\Models\Settings\VtbReport;
use App\Models\UserCategories\UserCpvs;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Admin\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\Admin\AdminUpdateRequest;
use App\Http\Requests\Admin\Admin\AdminSendMessage;
use App\Services\Admin\Admin\AdminService;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Mail\MailController;
use App\Jobs\ProcessAdminSendEmail;
use App\Jobs\ProccessForeachAdminSendEmail;
use App\Models\Tender\TenderStateArchive;
use Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends AbstractController
{

    /**
     * Display a listing of the resource.
     *
     */
    public function __construct(){
        $this->middleware('permission:admin');
    }
    /**
     * Show the application dashboard.
     *
     * @param Admin $admin
     * @return Renderable
     */
    public function index(Admin $admin){
        $adminList = $admin->get();
        return view('admin.admin.index',compact('adminList'));
    }
    /**
     * Show the application dashboard.
     *
     * @param Admin $admin
     * @return Renderable
     */
    public function create(Admin $admin){
        $permission = Permission::get()->groupBy('code');
        return view('admin.admin.add',compact('admin','permission'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreRequest $request
     * @param Admin $admin
     * @return Response
     */
    public function store(AdminStoreRequest $request,Admin $admin){
        $service = new AdminService($request);
        $service->createAdmin($admin);
        return redirect("/admin/admin");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id){
        $admin = Admin::findOrFail($id);
        return view('admin.admin.edit',compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id){
        $admin = Admin::findOrFail($id);
        return view('admin.admin.show',compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function getPermission(int $id){
        $admin = Admin::findOrFail($id);
        $adminPermissions = $admin->getAllPermissions()->groupBy('code');
        $permission = Permission::get()->groupBy('code');
        return view('admin.admin.permission',compact('admin','permission','adminPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id){

        $role = new AdminUpdateRequest();
        $v = $role->rules();
        $v["email"] = 'required|email|unique:admins,email,'.$id;
        $v["user_name"] = 'required|unique:admins,user_name,'.$id;
        $isValid = Validator::make($request->all(), [
            'name' => ['required'],
            'user_name' => ['required'],
            'password' => ['min:6'],
            'password_confirmation' => ['required_with:password|same:password|min:6'],
            'email' => ['required']
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }


        $service = new AdminService($request);
        $service->updateAdmin($id);
        return response()->json(['status' => true]);
        return redirect("/admin/admin");
    }
    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param int $id
     * @return Response
     *
     */
    public function updateAdminPermission(Request $request, int $id)
    {
        $service = new AdminService($request);
        $service->updatePermission($id);
        return redirect("/admin/admin");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id){
        Admin::findOrFail($id)->delete();
        return response()->json(['status' => true]);
    }

    public function sendMessage(){
        $categories = Cpv::orderby("code","ASC")->get();


        // Բոլոր
        $userTypeCounts['userType1Count'] = User::where("status","ACTIVE")->get()->count();
        // Պետական
        $userTypeCounts['userType2Count'] = User::where("status","ACTIVE")->where("type","STATE")->get()->count();
        // Մասնավոր
        $userTypeCounts['userType3Count'] = User::where("status","ACTIVE")->where("type","USER")->get()->count();
        // Մասնավորի Էկոնոմ
        $userTypeCounts['userType4Count'] = User::join("order","order.user_id","users.id")
                         ->where("order.package_id",2)
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->get()->count();
        // Մասնավորի Պրեմիում
        $userTypeCounts['userType5Count'] = User::join("order","order.user_id","users.id")
                         ->where("order.package_id",3)
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->get()->count();
        // Մասնավորի Գոլդ
        $userTypeCounts['userType6Count'] = User::join("order","order.user_id","users.id")
                         ->where("order.package_id",4)
                         ->where("order.payment_method", '!=', "trial period")
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->get()->count();
        // Մասնավորի Փորձաշրջանի
        $userTypeCounts['userType7Count'] = User::select('users.email')
                         ->join("order","order.user_id","users.id")
                         ->where("order.payment_method","trial period")
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->get()->count();
        // Պետականի Էկոնոմ
        $userTypeCounts['userType8Count'] = Organisation::select('users.email')
                         ->join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.package_id",18)
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->get()->count();
        // Պետականի Պրեմիում
        $userTypeCounts['userType9Count'] = Organisation::select('users.email')
                         ->join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.package_id",19)
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->get()->count();
        // Պետականի Գոլդ
        $userTypeCounts['userType10Count'] = Organisation::select('users.email')
                         ->join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.payment_method", '!=',  "trial period")
                         ->where("order_state.package_id",20)
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->get()->count();
        // Պետականի Փորձաշրջանի
        $userTypeCounts['userType11Count'] = Organisation::select('users.email')
                         ->join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.payment_method", "trial period")
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->get()->count();
        // Կատեգորիա չընտրածներ
        $userTypeCounts['userType12Count'] = User::where([['first_login', 1], ['type', "USER"]])
        ->get()->count();

        return view('admin.admin.sendMail',compact('categories', 'userTypeCounts'));
    }

    public function adminSendMessage(AdminSendMessage $request){
        ini_set('max_execution_time', 0);

        $validated = $request->validate([
            'title' => 'required',
            'text' => 'required',
            'userType' => 'required',
        ]);


        if($request->category == "0"){
            $userCategories = User::whereHas("cpvs")->get();
        }else{
            $userCategories = UserCpvs::join('users','users.id','=','user_cpvs.user_id')
                                            ->where('user_cpvs.cpv_id',$request->category)
                                            ->get();

            $emailParticipant = EmailParticipant::where("category", $request->category)->get();
        }

        // Բոլոր
        if( $request->userType == 1 ){
            $users = User::where("status","ACTIVE")->get();
        }elseif( $request->userType == 2 ){
            $users = User::where("status","ACTIVE")->where("type","STATE")->get();
        }elseif( $request->userType == 3 ){
            $users = User::where("status","ACTIVE")->where("type","USER")->get();
        }elseif( $request->userType == 4 ){
            $users = User::join("order","order.user_id","users.id")
                         ->where("order.package_id",2)
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 5 ){
            $users = User::join("order","order.user_id","users.id")
                         ->where("order.package_id",3)
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 6 ){
            $users = User::join("order","order.user_id","users.id")
                         ->where("order.package_id",4)
                         ->where("order.payment_method", '!=', "trial period")
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 7 ){
            $users = User::join("order","order.user_id","users.id")
                         ->where("order.payment_method","trial period")
                         ->whereDate("order.end_date",">=",date("Y-m-d"))
                         ->where("order.type","ACTIVE")
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 8 ){
            $users = Organisation::join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.package_id",18)
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 9 ){
            $users = Organisation::join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.package_id",19)
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 10 ){
            $users = Organisation::join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.payment_method", '!=',  "trial period")
                         ->where("order_state.package_id",20)
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 11 ){
            $users = Organisation::join("users","users.parent_id","users_state_organisation.id")
                         ->where("users.divisions",2)
                         ->join("order_state","order_state.organisation_id","users_state_organisation.id")
                         ->where("order_state.payment_method", "trial period")
                         ->where("order_state.type","ACTIVE")
                         ->whereDate("order_state.end_date",">=",date("Y-m-d"))
                         ->groupBy("users.email")
                         ->get();
        }elseif( $request->userType == 12 ){
            $users = User::where([['first_login', 1], ['type', "USER"]])
                        ->get();
        }


        $userEmails = explode(',', $request->additionals);
        $data = new \stdClass();
        $data->subject = $request->input('title');
        $data->text    = $request->input('text');
        ProccessForeachAdminSendEmail::dispatch($data, $users);
        ProccessForeachAdminSendEmail::dispatch($data, $userEmails);
        ProccessForeachAdminSendEmail::dispatch($data, $userCategories);
        if(isset($emailParticipant)){
            ProccessForeachAdminSendEmail::dispatch($data, $emailParticipant);
        }
        return redirect()->back()->with('message', 'Էլ․ նամակը հաջողությամբ ուղարկված է');
    }

    public function addParticipants(){
        $categories = Cpv::orderby("code","ASC")->get();
        return view('admin.admin.addParticipants', compact('categories'));
    }

    public function addNewParticipants(Request $request){

        $request->validate([
            'emails' => 'required',
        ]);

        $userEmails = explode(',', $request->emails);
        foreach($userEmails as $user){
            if(!empty($user)){                
                $emailParticipant = new EmailParticipant;
                $emailParticipant->category = $request->input('category');
                $emailParticipant->email = $user;
                $emailParticipant->save();
            }
        }
        return redirect()->back()->with('message', 'Էլ․ հասցեներ հաջողությամբ ավելացված է');
    }

    public function getParticipants(Request $request){

        $emails = EmailParticipant::get();
        return json_encode( array(
                'draw' => $_POST['draw'],
                'data' => $emails,
                'recordsFiltered' =>  count($emails),
                'recordsTotal' => count($emails)
            )
        );

    }

    public function deleteParticipantEmail($id){
        EmailParticipant::where('id',$id)->delete();
        return redirect()->back();
    }

    public function organizer(){
        return view('admin.admin.addOrganizer');
    }

    public function addOorganizer(Request $request){ 
        
        $request->validate([
            'organizer' => 'required',
            'name' => 'required',
        ]);

        $type = ($request->organizer == "1") ? 1 : 2 ;
        $organizer = new Organizator;
        $organizer->name = $request->input('name');
        $organizer->is_state = $type;
        $organizer->save();
        
         return redirect()->back()->with('message', 'Պատվիրատուն հաջողությամբ ավելացված է');   
    }

    public function getOrganizers(Request $request){
        $organizers = Organizator::orderBy("id","ASC")->offset($request->start)->take(10)->get();
            return json_encode( array(
                'draw' => $request['draw'],
                'data' => $organizers,
                'recordsFiltered' =>  count(Organizator::orderBy("id","ASC")->get()),
                'recordsTotal' => count($organizers)
                ) );
    }

    public function deleteOrganizator($id){
        Organizator::where("id",$id)->delete();
        return redirect()->back();
    }

    public function bankSecureStats(){
        $data = VtbReport::select("created_at as date")->get();
        $years = array();
        foreach($data as $val){
            $years[date("Y",strtotime($val->date))]['year'] = date("Y",strtotime($val->date));
        }
        ksort($years);
        $years = array_values($years);
        return view("admin.admin.bankSecureStats",compact('years'));
    }

    public function filterBankSecureStats(Request $request){
        $isValid = Validator::make($request->all(), [
            'year' => 'required',
            'month' => 'required',
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $date = $request->input("year")."-".$request->input("month");
        $res = array();
        $res["guaranted"] = VtbReport::where("action","guarantee request")->where("created_at","LIKE","%".$date."%")->count();
        $old_guaranted_tenders = TenderStateArchive::where("guaranteed","1")->where("created_at","LIKE","%".$date."%");
        $res["guaranted_tenders"] = TenderState::where("guaranteed","1")->where("created_at","LIKE","%".$date."%")->unionAll($old_guaranted_tenders)->count();
        $res["banner"] = VtbReport::where("action","banner click")->where("created_at","LIKE","%".$date."%")->count();
        $old_tenders = TenderStateArchive::where("is_competition","1")->where("created_at","LIKE","%".$date."%");
        $res["tenders"] = TenderState::where("is_competition","1")->where("created_at","LIKE","%".$date."%")->unionAll($old_tenders)->count();
        return  response()->json($res);
    }
}
