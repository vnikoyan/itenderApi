<?php

namespace App\Http\Controllers\Admin\Itender;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Itender\DefinedRequirements;
use App\Http\Requests\Itender\DefinedRequirementsRequest;
use App\Models\Cpv\Cpv;
use Illuminate\Http\Response;
use Yajra\Datatables\Datatables;
use App\Models\Translation\Language;

class DefinedRequirementsController extends AbstractController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function __construct(){
        $this->middleware('permission:cpv');
    }

    /**
     * Show the application dashboard.
     *
     * @param DefinedRequirements $defined_requirements
     * @return Renderable
     */
    public function index(DefinedRequirements $defined_requirements){
        return view('admin.defined_requirements.index',compact("defined_requirements"));
    }

    /**
     * Show the application dashboard.
     *
     * @param int $id
     * @return Renderable
     */
    public function getByCatId($id = 1){
        $defined_requirementsList = DefinedRequirements::where("cpv_id",$id)->get();
        // dd(json_decode($defined_requirementsList[0]->valueOrder,true));
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.defined_requirements.getByCatId',compact("id","defined_requirementsList","language"));
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Renderable
     */
    public function getByAjax(Request $request){
        return Cpv::where("name", 'like','%'.$request->term["term"].'%')->limit(30)->get()->toJson();
    }

    /**
     * Show the application dashboard.
     *
     * @param DefinedRequirements $defined_requirements
     * @return Renderable
     */
    public function create(DefinedRequirements $defined_requirements){
        return view('admin.defined_requirements.add',compact('defined_requirements'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DefinedRequirementsRequest $request
     * @param DefinedRequirements $defined_requirements
     * @return void
     */
    public function store(DefinedRequirementsRequest $request,DefinedRequirements $defined_requirements){
        $this->addEdit($defined_requirements,$request);
        return redirect()->back();
    }
    function addEdit($defined_requirements,$request){
        foreach($request->title as $key => $value){
            $defined_requirements->setTranslation('title', $key , $value);
        }
        $defined_requirements->value = json_encode($request->value);
        $defined_requirements->valueOrder = json_encode($request->valueOrder);
        $defined_requirements->order = $request->order;
        $defined_requirements->cpv_id = $request->cpv_id;
        $defined_requirements->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id){
        $defined_requirements = DefinedRequirements::findOrFail($id);
        $cpv = Cpv::where("id",$defined_requirements->cpv_id)->pluck('name', 'id');
        return view('admin.defined_requirements.edit',compact('defined_requirements','cpv'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function show(){
        return redirect("/admin/cpv");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DefinedRequirementsRequest $request
     * @param int $id
     * @return Response
     */
    public function update(DefinedRequirementsRequest $request, int $id){
        $defined_requirements = DefinedRequirements::findOrFail($id);
        $this->addEdit($defined_requirements,$request);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id){
        DefinedRequirements::findOrFail($id)->delete();
        return response()->json(["status"=>true]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function tableData(){
        $tableData =  Datatables::of(DefinedRequirements::select('*'));
        return $tableData->addColumn('action', function ($defined_requirements) {
            return '<a href="/admin/defined_requirements/'.$defined_requirements->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>
                    <a href="#" data-tableName="userTable" data-href="/admin/defined_requirements/delete/'.$defined_requirements->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
        })
        ->make(true);
    }
}
