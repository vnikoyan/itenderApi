<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\User\User;
use App\Models\User\Organisation;
use App\Models\Order\OrderState;
use App\Models\Package\PackageState;
use App\Models\Package\Package;
use Yajra\Datatables\Datatables;
use App\Http\Requests\User\Package\PackageStateAddEditRequest;
use Auth;
use Validator;

class PackageStateController extends AbstractController
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
    public function index(){
        return view('admin.package_state.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(PackageState $packages){
        return view('admin.package_state.add',compact('packages'));
    }

   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageStateAddEditRequest $request,PackageState $packages){
        $packages->name     = $request->name;
        $packages->price    = $request->price;
        $packages->save();
        return redirect("/admin/package_state");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $packages = PackageState::findOrFail($id);
        return view('admin.package_state.edit',compact('packages'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(PackageStateAddEditRequest $request,$id){
        $packages = PackageState::findOrFail($id);
        $packages->name     = $request->name;
        $packages->price    = $request->price;
        $packages->save();
        return redirect("/admin/package_state");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        PackageState::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }

     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(PackageState::select('*')->orderby("id","ASC"));
        return $tableData->addColumn('action', function ($package) {
                     return '<a href="/admin/package_state/'.$package->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Խմբագրել</a>
                             <a href="#" data-tableName="userTable" data-href="/admin/package_state/delete/'.$package->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }

    public function addPackageStateView(){
        $packagesState = PackageState::orderBy("price","ASC")->get();
        return view('admin.package_state.add_package_state',compact('packagesState'));
    }

    public function searchByName(Request $request){
        $user = Organisation::where('name','like', '%' . $request->input('name') . '%')->get();
        return json_encode($user); 
    }

   public function addPackageView(){
        $packages = Package::orderBy("id","ASC")->get();
        return view('admin.package.add_package',compact('packages'));
   }
    public function addPackageState(Request $request){
        $validated = $request->validate([
            'package' => 'required',
            'price' => 'required',
            'name' => 'required',
            'og-id' => 'required',
        ]);
        
        $organisation = Organisation::where("id",$request->input('og-id'))->first();
        $package = PackageState::where("id", $request->input("price"))->first();
        $orderState = new OrderState;
        $orderState->strat_date = date("Y-m-d H:i:s");
        $orderState->amount_paid = $package->price;
        $orderState->type = "ACTIVE";
        $orderState->deleted_at = NULL;
        $orderState->package_id = $package->id;
        $orderState->organisation_id = $organisation->id;
        $orderState->quantity = $package->quantity;
        $orderState->save();

        return redirect()->back()->with('success', 'Փաթեթը հաջողությամբ ավելացված է');   
    }

}
