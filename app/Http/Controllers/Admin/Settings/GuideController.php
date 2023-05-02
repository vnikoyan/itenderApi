<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Guide;
use App\Models\Translation\Language;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\Guide\GuideStoreAndUpdateRequest;
use Auth;

class GuideController extends AbstractController
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
        return view('admin.settings.guide.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Guide $guide){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.guide.add',compact('guide','language'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuideStoreAndUpdateRequest $request,Guide $guide){

        
        $file_ = $request->file('file');
        foreach($request->title as $key => $value){
            $guide->setTranslation('title', $key , $value);
            $guide->setTranslation('description', $key , $request->description[$key]);
            $guide->setTranslation('youtube_link', $key , $request->youtube_link[$key]);
            if(!empty($file_[$key])){
                $filenameWithExt = $file_[$key]->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $file_[$key]->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                $path = $file_[$key]->storeAs('/public/guide',$fileNameToStore);
                $guide->setTranslation('file', $key , $fileNameToStore);
            }
        }
        $guide->order         = 0;
        $guide->save();
        
        
        return redirect("/admin/guide");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $guide = Guide::findOrFail($id);
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.guide.edit',compact('guide','language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(GuideStoreAndUpdateRequest $request,$id){
        $guide = Guide::findOrFail($id);
        
        $file_ = $request->file('file');
        foreach($request->title as $key => $value){
            $guide->setTranslation('title', $key , $value);
            $guide->setTranslation('description', $key , $request->description[$key]);
            $guide->setTranslation('youtube_link', $key , $request->youtube_link[$key]);
            if(!empty($file_[$key])){
                $filenameWithExt = $file_[$key]->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $file_[$key]->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                $path = $file_[$key]->storeAs('/public/guide',$fileNameToStore);
                $guide->setTranslation('file', $key , $fileNameToStore);
            }
        }
        $guide->order         = 0;

        $guide->save();
        
        return redirect("/admin/guide");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        Guide::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(Guide::orderBy('order')->select('title','id',"order"));
        return $tableData->addColumn('action', function ($guide) {
                     return '<a href="/admin/guide/'.$guide->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                             <a href="#" data-tableName="userTable" data-href="/admin/guide/delete/'.$guide->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->addColumn('title', function ($guide) {
                    return $guide->title;
                })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function fileDownload($id,$lg){
        return \Storage::download("public/guide/".Guide::findOrFail($id)->getTranslation("file",$lg));
    }

}