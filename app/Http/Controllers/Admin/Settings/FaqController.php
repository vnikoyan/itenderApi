<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Faq;
use App\Models\Translation\Language;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\Info\InfoStoreAndUpdateRequest;
use Auth;

class FaqController extends AbstractController
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
        return view('admin.settings.faq.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Faq $faq){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.faq.add',compact('faq','language'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InfoStoreAndUpdateRequest $request,Faq $faq){
        foreach($request->title as $key => $value){
            $faq->setTranslation('title', $key , $value);
            $faq->setTranslation('description', $key , $request->description[$key]);
        }
        $faq->order         = $request->order;
        $faq->save();
        return redirect("/admin/faq");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $faq = Faq::findOrFail($id);
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.faq.edit',compact('faq','language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(InfoStoreAndUpdateRequest $request,$id){
        $faq = Faq::findOrFail($id);
        foreach($request->title as $key => $value){
            $faq->setTranslation('title', $key , $value);
            $faq->setTranslation('description', $key , $request->description[$key]);
        }
        $faq->order         = $request->order;
        $faq->save();
        return redirect("/admin/faq");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        Faq::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(Faq::orderBy('order')->select('title','id',"order"));
        return $tableData->addColumn('action', function ($faq) {
                     return '<a href="/admin/faq/'.$faq->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                             <a href="#" data-tableName="userTable" data-href="/admin/faq/delete/'.$faq->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->addColumn('title', function ($faq) {
                    return $faq->title;
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
        return \Storage::download("public/faq/".Faq::findOrFail($id)->file);
    }
}