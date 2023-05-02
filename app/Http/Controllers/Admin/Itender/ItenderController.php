<?php
namespace App\Http\Controllers\Admin\Itender;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Itender\Itender;
use App\Models\Itender\ItenderItem;
use App\Models\Itender\ItenderTerms;
use App\Models\Organize\OrganizeItender;
use App\Models\Organize\OrganizeRow;
use App\Models\Tender\TenderState;
use App\Services\Admin\Itender\ItenderService;
use Yajra\Datatables\Datatables;
use Auth;
use Validator;

class ItenderController extends AbstractController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:itender');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Itender $itender,$type="NEW"){
        return view('admin.itender.index',compact('itender','type'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function time(){
        $itenderTerms = ItenderTerms::findOrFail(1);
        return view('admin.itender.time',compact('itenderTerms'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function timeUpdate(Request $request){
        $v = Validator::make($request->all(), array(
            'min' => 'required|numeric|min:1',
            'max' => 'required|numeric|min:1',
        ));
        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }
        $itenderTerms = ItenderTerms::findOrFail(1);
        $itenderTerms->min = $request->min;
        $itenderTerms->max = $request->max;
        $itenderTerms->save();
        return redirect("/admin/itender/time");
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Itender $itender){
        return view('admin.itender.add',compact('itender'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItenderStoreAndUpdateRequest $request, Itender $itender){
      
        // $blackList->name       = $request->name;
        // $blackList->start_date = $request->start_date;
        // $blackList->end_date   = $request->end_date;
        // $blackList->address    = $request->address;
        // $blackList->info       = $request->info;
        // $blackList->for_what   = $request->for_what;
        $blackList->save();
        return redirect("/admin/itender");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $blackList = Itender::findOrFail($id);
        return view('admin.itender.edit',compact('itender'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function check($id){
        $itender = Itender::findOrFail($id);
        $itender->type = "APPROVED";
        $itender->save();
        return redirect("/admin/itender");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function getByid(Request $request){
        return OrganizeRow::where("organize_id",$request->id)->with('cpv')->with('procurementPlan')->get();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(ItenderStoreAndUpdateRequest $request,$id){
        return redirect("/admin/itender");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
        $organize = OrganizeItender::findOrFail($id);
        TenderState::where('one_person_organize_id', $id)->delete();
        $organize->delete();
        return json_encode(["status"=>true]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function rejected($id,Request $request){
        $itender = Itender::findOrFail($id);
        $itender->type="REJECTED";
        $itender->rejected = $request->rejected;
        $itender->save();
        return json_encode(["status"=>true]);
    }
    
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function tableData($type = "NEW"){
        $tableData =  Datatables::of(OrganizeItender::where('publication', 'senden')->with('organizeRows')->with('user')->orderBy('updated_at', 'DESC'));
        return $tableData->addColumn('action', function ($itender) use($type) {
                    // if($type == "NEW"){
                    //     return '<a href="#'.$itender->id.'" data-id="'.$itender->id.'" title="Դիտել" class="btn btn-xs btn-primary viewTneder"><i class="fa fa-eye"></i></a> 
                    //          <a href="/admin/itender/'.$itender->id.'/check" title="Հաստատել" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></a> 
                    //          <a href="#" data-tableName="userTable" id="id__'.$itender->id.'" title="Մերժել/Ջնջել" data-id="'.$itender->id.'" data-href="/admin/itender/delete/'.$itender->id.'"  class="btn btn-xs btn-danger waves-effect waves-light cancelAndDeleteItem"><i class="fa fa-ban"></i></a>';
                    // }else{
                        return '
                        <a href="#'.$itender->id.'" data-id="'.$itender->id.'" title="Դիտել" class="btn btn-xs btn-primary viewTneder"><i class="fa fa-eye"></i></a>
                        <a href="#" data-tableName="userTable" id="id__'.$itender->id.'" title="Ջնջել" data-id="'.$itender->id.'" data-href="/admin/itender/delete/'.$itender->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-ban"></i></a>';
                    // }                        
                })->editColumn('name', function ($itender) {
                    return $itender->name;
                })
        ->make(true);
    }
}