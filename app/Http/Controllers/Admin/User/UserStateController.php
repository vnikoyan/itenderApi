<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\AbstractController;
use App\Models\User\User;
use App\Models\User\Organisation;
use App\Models\Package\Package;
use App\Models\Order\OrderState;
use App\Models\Package\PackageState;
use App\Http\Requests\User\User\UserStateStoreDivisionsRequest;
use App\Http\Requests\User\User\UserStateUpdateRequest;
use App\Services\User\User\UserService;
use Exception as ExceptionAlias;
use Illuminate\Support\Facades\Log;
use Storage;
use Yajra\Datatables\Datatables;

class UserStateController extends AbstractController
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->middleware('permission:user_state');
        return  true;
    }

    /**
     * Show the application dashboard.
     * @param string $type
     * @return Illuminate\Contracts\View\View
     */
    public function index($type = "all"){
        return view('admin.user.state.index',compact('type'));
    }

    /**
     * Show the application dashboard.
     * @param User $users
     * @return Renderable
     */
    public function create(User $users){
        $package = Package::pluck("name",'id')->toArray();
        $usersStateRoot = User::whereNull("parent_id")->where("type","STATE")->pluck("name",'id')->toArray();

        return view('admin.user.state.add',compact('users','package','usersStateRoot'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(){
        return redirect("/admin/user_state");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id){
        $organisation = Organisation::with('userNoG')->findOrFail($id);
        $divisions = [];
        $divisionsSt = ["Պատասխանատու ստորաբաժանում","Համակարգող","Բաժին","Վարչություն"];
        $package = PackageState::orderBy("id","ASC")->get();
        $startDate = date("Y-m-d");
        $endDate = date('Y-m-d', strtotime('+1 years'));
        $order = OrderState::where("type","ACTIVE")->where("organisation_id",$id)->where("payment_method","!=","trial period")->whereDate("end_date",">=",date("Y-m-d"))->orderBy("id","DESC")->first();
        $orderTrial = OrderState::where("type","ACTIVE")->where("organisation_id",$id)->where("payment_method","=","trial period")->whereDate("end_date",">=",date("Y-m-d"))->orderBy("id","DESC")->first();
        foreach ($organisation->userNoG as $key => $value){
            $divisions[$divisionsSt[$value->divisions-1]][] =  $value;
        }
        return view('admin.user.state.edit',compact('organisation','divisions','package','startDate','endDate',"order","orderTrial"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id){
        $user = User::findOrFail($id);
        return view('admin.user.state.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserStateUpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UserStateUpdateRequest $request, int $id){
        $service = new UserService($request);
        $user =  $service->updateUser($id);
        return redirect('/admin/user_state/'.$user->parent_id."/edit");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserStateUpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function editOrg(UserStateUpdateRequest $request, int $id){
        $service = new UserService($request);
        $user =  $service->updateStateUser($id);
        return redirect('/admin/user_state/'.$user->id."/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id){
        User::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }

    public function contrat($id){
        return Storage::download("public/contract/".User::findOrFail($id)->contrat->file);
    }

    public function addDivisions(UserStateStoreDivisionsRequest $request,User $user,$organisation_id){
        $service = new UserService($request);
        $service->createStateDivisionsUser($user,$organisation_id);
        return redirect()->back();
    }

    public function tableData(){
            $tableData = Organisation::whereHas('userNoG', function($q){
                $q->where('type', 'STATE');
            });
            $tableData = Datatables::of($tableData->orderby("created_at","DESC"));
            return $tableData->addColumn('action', function ($user) {
                        if(!empty($user->contrat)){
                            $html = '<a href="/admin/user_state/contrat/'.$user->id.'" class="btn btn-xs btn-primary" data-placement="top" title="Պայմանագիր" data-original-title="Պայմանագիր" data-trigger="hover"><i class="fa fa-file"></i> </a> ';
                        }else$html = '<a href="#" class="btn btn-xs btn-warning warningLink" data-footer="'.$user->name.'" data-toggle="tooltip-custom" data-placement="top" title="Պայմանագիր" data-original-title="Պայմանագիր" data-trigger="hover"  " data-text="Պայմանագիր չկա!"><i class="fa fa-file"></i> </a> ';
                         return $html.' <a href="/admin/user_state/'.$user->id.'/edit" class="btn btn-xs btn-primary" data-toggle="tooltip-custom" data-placement="top" title="Դիտել" data-original-title="Դիտել" data-trigger="hover"><i class="fas fa-eye"></i></a><a href="/admin/user_state/delete/'.$user->id.'" class="btn btn-xs btn-danger" data-toggle="tooltip-custom" data-placement="top" title="Ջնջել" data-original-title="Ջնջել" data-trigger="hover"><i class="fas fa-trash"></i></a>';
                    })->addColumn('name', function ($user) {
                        $username = $user->name;
                        $findme   = $user->company_type;
                        if(str_contains($username, $findme) !== false){
                            return $username;
                        }else{
                            return $username.' '.$user->company_type;
                        }
                        return $user->name;
                    })->filterColumn('name', function($query, $keyword) {
                        // $sql = 'JSON_UNQUOTE(json_extract(LOWER(`name`), "$.hy")) LIKE ?';
                        // $query->whereRaw($sql, ["%{$keyword}%"]);
                    })->addColumn('address', function ($user) {
                        return $user->address;
                    })->editColumn('id', 'ID: {{$id}}')
                    ->make(true);
    }

    public function removeUser($id){ 
        Organisation::where("id",$id)->delete();
        User::where("parent_id",$id)->delete();
        return redirect()->back();
    }
}
