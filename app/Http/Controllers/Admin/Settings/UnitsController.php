<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Units;
use App\Models\Translation\Language;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\Units\UnitsStoreAndUpdateRequest;
use Auth;

class UnitsController extends AbstractController
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
        return view('admin.settings.units.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Units $units){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);

        return view('admin.settings.units.add',compact('units','language'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitsStoreAndUpdateRequest $request,Units $units){
        foreach($request->units as $key => $value){
            $units->setTranslation('title', $key , $value);
        }
        $units->order = $request->order;
        $units->save();
        return redirect("/admin/units");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $units = Units::findOrFail($id);
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.units.edit',compact('units','language'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(UnitsStoreAndUpdateRequest $request,$id){
        $units = Units::findOrFail($id);
        foreach($request->units as $key => $value){
            $units->setTranslation('title', $key , $value);
        }
        $units->order = $request->order;
        $units->save();

        return redirect("/admin/units");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        Units::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(Units::orderBy('order')->select('*'));
        return $tableData->addColumn('action', function ($units) {
                    if($units->type == 1){
                     return '<a href="/admin/units/'.$units->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Խմբագրել</a> <a href="#" data-tableName="userTable" data-href="/admin/units/delete/'.$units->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i>Ջնջել</a>';
                    }else{
                        return "";
                    }
                 })->addColumn('title', function ($units) {
                    return $units->title;
                })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }
}