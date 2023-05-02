<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Info;
use App\Models\Translation\Language;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\Info\InfoStoreAndUpdateRequest;
use Auth;

class InfoController extends AbstractController
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
        return view('admin.settings.info.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Info $info){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.info.add',compact('info','language'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InfoStoreAndUpdateRequest $request,Info $info){
        foreach($request->title as $key => $value){
            $info->setTranslation('title', $key , $value);
            $info->setTranslation('description', $key , $request->description[$key]);
        }
        $info->order         = $request->order;
        $info->save();
        return redirect("/admin/info");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $info = Info::findOrFail($id);
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.info.edit',compact('info','language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(InfoStoreAndUpdateRequest $request,$id){
        $info = Info::findOrFail($id);
        foreach($request->title as $key => $value){
            $info->setTranslation('title', $key , $value);
            $info->setTranslation('description', $key , $request->description[$key]);
        }
        $info->order         = $request->order;
        $info->save();
        return redirect("/admin/info");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        Info::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(Info::orderBy('order')->select('title','id',"order"));
        return $tableData->addColumn('action', function ($info) {
                     return '<a href="/admin/info/'.$info->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                             <a href="#" data-tableName="userTable" data-href="/admin/info/delete/'.$info->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->addColumn('title', function ($info) {
                    return $info->title;
                })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function fileDownload($id){
        return \Storage::download("public/info/".Info::findOrFail($id)->file);
    }
}