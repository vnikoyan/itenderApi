<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Classifier;
use App\Models\Settings\ClassifierCpv;
use App\Models\Cpv\Cpv;
use App\Services\Admin\Settings\ClassifierService;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\Classifier\ClassifierStoreAndUpdateRequest;
use App\Http\Requests\Settings\Classifier\ClassifierUploadeFileRequest;
use Auth;

class ClassifierController extends AbstractController
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
    public function index(Classifier $classifier){
        return view('admin.settings.classifier.index',compact('classifier'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Classifier $classifier){
        return view('admin.settings.classifier.add',compact('classifier'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassifierStoreAndUpdateRequest $request, Classifier $classifier){
        $classifier->title       = $request->title;
        $classifier->code      = $request->code;
        $classifier->save();
        $classifierCpv = [];
        foreach($request->cpv_id as $key => $value){
            $classifierCpv = new ClassifierCpv();
            $classifierCpv->cpv_id       = $value;
            $classifierCpv->classifier_id = $classifier->id;
            $classifierCpv->save();
        }
        return redirect("/admin/classifier");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $classifier = Classifier::with("cpv")->findOrFail($id);
        $cpv = Cpv::whereIn("id",$classifier->cpv()->pluck('cpv_id')->toArray())->pluck('name', 'id');
        return view('admin.settings.classifier.edit',compact('classifier','cpv'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\ResponseClassifier
    */
    public function update(ClassifierStoreAndUpdateRequest $request,$id){
        $classifier = Classifier::findOrFail($id);
        
        $classifier->title       = $request->title;
        $classifier->code      = $request->code;
        $classifier->save();
        $classifierCpv = [];
        ClassifierCpv::where("classifier_id",$id)->delete();
        foreach($request->cpv_id as $key => $value){
            $classifierCpv = new ClassifierCpv();
            $classifierCpv->cpv_id       = $value;
            $classifierCpv->classifier_id = $classifier->id;
            $classifierCpv->save();
        }
        return redirect("/admin/classifier");

        return redirect("/admin/classifier");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function fileUploade(ClassifierUploadeFileRequest $request){
        $service = new BlackListService($request);
        $service->createByFileUploade(); 

        return redirect("/admin/classifier");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        Classifier::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
    
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function tableData(){
        $tableData =  Datatables::of(Classifier::select('*'));
        return $tableData->addColumn('action', function ($classifier) {
                     return '<a href="/admin/classifier/'.$classifier->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել </a> 
                        <a href="#" data-tableName="userTable" data-href="/admin/classifier/delete/'.$classifier->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->make(true);
        // ->editColumn('id', 'ID: {{$id}}')
    }
}