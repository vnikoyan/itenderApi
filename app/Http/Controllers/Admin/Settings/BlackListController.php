<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\BlackList;
use App\Models\User\User;
use App\Services\Admin\Settings\BlackListService;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\BlackList\BlackListStoreAndUpdateRequest;
use App\Http\Requests\Settings\BlackList\BlackListUploadeFileRequest;
use Auth;

class BlackListController extends AbstractController
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
    public function index(BlackList $blackList){

        return view('admin.settings.blackList.index',compact('blackList'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(BlackList $blackList){
        return view('admin.settings.blackList.add',compact('blackList'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlackListStoreAndUpdateRequest $request, BlackList $blackList){

        User::where('id', $request->user_id)->update(['status' => 'BLOCK']);

        $blackList->name       = $request->name;
        $blackList->start_date = $request->start_date;
        $blackList->end_date   = $request->end_date;
        $blackList->address    = $request->address;
        $blackList->info       = $request->info;
        $blackList->for_what   = $request->for_what;
        $blackList->user_id   =  $request->user_id;
        $blackList->save();
        return redirect("/admin/black_lists");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $blackList = BlackList::select("black_list.*")->where("black_list.id",$id)->first();
        return view('admin.settings.blackList.edit',compact('blackList'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(BlackListStoreAndUpdateRequest $request,$id){
        $blackList = BlackList::findOrFail($id);
        $blackList->name       = $request->name;
        $blackList->start_date = $request->start_date;
        $blackList->end_date   = $request->end_date;
        $blackList->address    = $request->address;
        $blackList->info       = $request->info;
        $blackList->for_what   = $request->for_what;

        $blackList->save();

        return redirect("/admin/black_lists");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function fileUploade(BlackListUploadeFileRequest $request){
        $service = new BlackListService($request);
        $service->createByFileUploade(); 

        return redirect("/admin/black_lists");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        BlackList::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
    
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function tableData(){
        $tableData =  Datatables::of(BlackList::select('*')->orderby('id','DESC'));
        return $tableData->addColumn('action', function ($blackList) {
                     return '<a href="/admin/black_lists/'.$blackList->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել </a> 
                        <a href="#" data-tableName="userTable" data-href="/admin/black_lists/delete/'.$blackList->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })
        ->make(true);
    }
}