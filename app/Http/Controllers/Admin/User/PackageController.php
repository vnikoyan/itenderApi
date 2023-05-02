<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\User\User;
use App\Models\Package\Package;
use Yajra\Datatables\Datatables;
use App\Models\User\Organisation;
use Auth;

class PackageController extends AbstractController
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
        return view('admin.package.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Package $packages){
        return view('admin.package.add',compact('packages'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $packages = Package::findOrFail($id);
        return view('admin.package.edit',compact('packages'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request,$id){
        $packages = Package::findOrFail($id);
        $packages->price_1 = $request->price_1;
        $packages->price_3 = $request->price_3;
        $packages->price_6 = $request->price_6;
        $packages->price_12 = $request->price_12;
        $packages->save();
        return redirect("/admin/package");
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(Package::select('*'));
        return $tableData->addColumn('action', function ($package) {
                     return '<a href="/admin/package/'.$package->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Խմբագրել</a> ';
                 })->addColumn('package', function ($package) {
                     $packageName = [
                        1 => "Անվճար",
                        2 => "Էկոնոմ",
                        3 => "Պրեմիում",
                        4 => "Գոլդ",
                     ];
                     return $packageName[$package->id];
                 })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }

    public function searchUser(Request $request){
        $searchUser = $request->input("val");
        $data = User::where('tin','LIKE', '%'. $searchUser .'%')->orWhere('email','LIKE', '%'. $searchUser .'%')->orwhere("name",'LIKE', '%'. $searchUser .'%')->get();
        return json_encode($data);
    }


}
