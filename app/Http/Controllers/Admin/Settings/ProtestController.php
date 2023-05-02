<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Protest;
use App\Models\Translation\Language;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\Info\InfoStoreAndUpdateRequest;
use Auth;

class ProtestController extends AbstractController
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
        return view('admin.settings.protest.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Protest $protest){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.protest.add',compact('protest','language'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InfoStoreAndUpdateRequest $request,Protest $protest){
        foreach($request->title as $key => $value){
            $protest->setTranslation('title', $key , $value);
            $protest->setTranslation('description', $key , $request->description[$key]);
        }
        $protest->order         = $request->order;
        $protest->save();
        return redirect("/admin/protest");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $protest = Protest::findOrFail($id);
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.protest.edit',compact('protest','language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(InfoStoreAndUpdateRequest $request,$id){
        $protest = Protest::findOrFail($id);
        foreach($request->title as $key => $value){
            $protest->setTranslation('title', $key , $value);
            $protest->setTranslation('description', $key , $request->description[$key]);
        }
        $protest->order         = $request->order;
        $protest->save();
        return redirect("/admin/protest");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        Protest::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(Protest::orderBy('order')->select('title','id',"order"));
        return $tableData->addColumn('action', function ($protest) {
                     return '<a href="/admin/protest/'.$protest->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                             <a href="#" data-tableName="userTable" data-href="/admin/protest/delete/'.$protest->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->addColumn('title', function ($protest) {
                    return $protest->title;
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
        return \Storage::download("public/protest/".Protest::findOrFail($id)->file);
    }
}