<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\CoWorkers;
use App\Models\User\User;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\CoWorkers\CoWorkersStoreAndUpdateRequest;
use Auth;

class CoWorkersController extends AbstractController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:settings');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $coworkers = CoWorkers::with("user")->orderBy('status')->get();
        return view('admin.settings.coWorkers.index',compact('coworkers'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(CoWorkers $coworkers){
        $users = User::pluck("email",'id')->toArray();

        return view('admin.settings.coWorkers.add',compact('coworkers',"users"));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CoWorkersStoreAndUpdateRequest $request,CoWorkers $coworkers){
 
        $coworkers->user_id = $request->user_id;
        $coworkers->address = $request->address;
        $coworkers->website = $request->website;
        $coworkers->cpv     = $request->cpv;
        
        if(!empty($request->file('image'))){
            $value = $request->file('image');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/coWorkers',$fileNameToStore,"publicP");
            $coworkers->image = $fileNameToStore;
        }

        $coworkers->save();
        return redirect("/admin/co_workers");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $coworkers = CoWorkers::findOrFail($id);
        $users = User::pluck("email",'id')->toArray();

        return view('admin.settings.coWorkers.edit',compact('coworkers',"users"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(CoWorkersStoreAndUpdateRequest $request,$id){
        $coworkers = CoWorkers::findOrFail($id);

        $coworkers->user_id = $request->user_id;
        $coworkers->address = $request->address;
        $coworkers->website = $request->website;
        $coworkers->cpv = $request->cpv;
        
        if(!empty($request->file('image'))){
            $value = $request->file('image');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/coWorkers',$fileNameToStore,"publicP");
            $coworkers->image = $fileNameToStore;
        }
        $coworkers->save();
        return redirect("/admin/co_workers");
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function editStatus($id){
        $coworkers = CoWorkers::findOrFail($id);
        $coworkers->status = 1;
        $coworkers->save();
        return redirect("/admin/co_workers");
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        CoWorkers::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(CoWorkers::orderBy('order')->select('*'));
        return $tableData->addColumn('action', function ($coworkers) {
                    if($coworkers->type == 1){
                     return '<a href="/admin/co_workers/'.$coworkers->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Խմբագրել</a> <a href="#" data-tableName="userTable" data-href="/admin/co_workers/delete/'.$coworkers->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i>Ջնջել</a>';
                    }else{
                        return "";
                    }
                 })->addColumn('title', function ($coworkers) {
                    return $coworkers->title;
                })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }
}