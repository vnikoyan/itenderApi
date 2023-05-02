<?php

namespace App\Http\Controllers\Admin\Cpv;


use App\Http\Controllers\Admin\AbstractController;
use App\Imports\ProcurementPlanImport;
use App\Models\Cpv\Cpv;
use App\Models\Translation\Language;
use App\Models\Cpv\Specifications;
use App\Http\Requests\Cpv\CpvRequest;
use App\Http\Requests\Cpv\CpvTranslateRequest;
use App\Jobs\ClearCpvsPotential;
use App\Jobs\UploadCpvsPotential;
use App\Models\Tender\TenderStateCpv;
use App\Services\Admin\Cpv\CpvService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Yajra\Datatables\Datatables;
use App\Models\Itender\DefinedRequirements;
use App\Models\Settings\Region;
use App\Models\Settings\Units;
use Illuminate\Support\Facades\Log;

class CpvController extends AbstractController
{
    public function __construct(){
        $this->middleware('permission:cpv');
    }
    /**
     * Show the application dashboard.
     *
     * @param Cpv $cpv
     * @return Renderable
     */
    public function index(Cpv $cpv){
        $regions = Region::orderBy('id','ASC')->get();
        $units  = Units::orderBy('id','ASC')->get();
        return view('admin.cpv.index',compact("cpv", 'regions', 'units'));
    }
    /**
     * Show the application dashboard.
     * @param int $type = 1
     * @param Specifications $specifications
     * @param DefinedRequirements $definedRequirements
     * @return Renderable
     */
    public function tree($type = 1,Specifications $specifications,DefinedRequirements $definedRequirements){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.cpv.tree',compact("type","specifications","language","definedRequirements"));
    }
    public function treeJson($type){
        $tree = Cpv::where("type",$type)->where('parent_id',0)->with('children')->get();
        return  $tree->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CpvRequest $request
     * @return Response
     */
    public function fileUpload(CpvRequest $request){
        $service = new CpvService($request);
        $service->uploade();
        return redirect("/admin/cpv");
    }

    /**
     * Show the application dashboard.
     *
     * @param User $users
     * @return void
     */
    public function create(User $users){

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(){
        return redirect("/admin/cpv");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(){
        return redirect("/admin/cpv");
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
     * @return Response
     */
    public function update(){
        return redirect("/admin/cpv");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(){
        // User::findOrFail($id)->delete();
        return response()->json(['status' => true]);
    }

    /**
     * Process datatables ajax request.
     *
     * @param int $type
     * @return JsonResponse
     * @throws Exception
     */
    public function tableData($type=null){
        if(is_null($type)){
            $tableData =  Datatables::of(Cpv::select('*')->with('specificationsWithStatistics'));
        }else{
            $tableData =  Datatables::of(Cpv::where("type",$type)->select('*')->with('specificationsWithStatistics'));
        }
        return $tableData->addColumn('action', function ($cpv) {
            $used_count = TenderStateCpv::where('cpv_id', $cpv->id)
            ->whereHas('tender', function ($query) {
                return $query->where('is_competition', 1);
            })->count();
            $potential = json_encode([
                'used_count' => $used_count,
                'cpv_id' => $cpv->id, 
                'potential_electronic' => $cpv->potential_electronic, 
                'potential_paper' => $cpv->potential_paper,
                'updated' => $cpv->updated_at ? date_format($cpv->updated_at,"d.m.Y") : '',
            ]);
            return "
            <div>
                <button data='{$potential}' data-toggle='modal' data-target='#potentialModal' class='btn btn-xs btn-primary open-potential-modal'>
                    Շուկայի պոտենցիալ <i class='ml-1 fas fa-coins'></i>
                </button>
                <button data='{$cpv->specificationsWithStatistics}' potential='{$potential}' data-toggle='modal' data-target='#statisticsModal' class='btn btn-xs btn-primary open-statistics-modal'>
                    Վիճակագրություն <i class='ml-1 fa fa-chart-line'></i>
                </button>
            </div>";
        })->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }

    public function getByCpvType(Int $cpv_id)
    {
        $cpv = Cpv::find($cpv_id);
        switch (+$cpv->type) {
            case '1':
                return 'product';
            case '1':
                return 'service';
            case '3':
                return 'work';
            default:
                break;
        }
    }

    public function uploadPotentialClear()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        ClearCpvsPotential::dispatch();
        return 'success';
    }

    public function uploadPotential(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        $rows = \Excel::toArray(new ProcurementPlanImport, $request->file);
        if(count($rows)){
            $data = $rows[0];
		    unset($data[0]);
            // Cpv::query()->update(['potential_electronic' => 0]);
            // ClearCpvsPotential::dispatch($data);
            UploadCpvsPotential::dispatch($data);
            // return $data;
        }
        return 'success';
    }

    public function getCpvByText(Request $request){

        preg_match_all('/([^\s]+)/',$request->cpv,$match,PREG_PATTERN_ORDER);
        $data = [];
        $cp = [];
        foreach($match[0] as $val){
            $code_w_slash = $val;
            $code = explode("/",$val) ;
            $val = (count($code) == 2) ? trim($code[0]) : trim($val);
            $data[]["code"] = $code_w_slash;
            $cp[] = $val;
        }
        
        $cpvs = Cpv::whereIn("code", $cp)->get();
        foreach($data as $key => $val){
            $code = explode("/",$val['code']) ;
            $val = (count($code) == 2) ? trim($code[0]) : trim($val['code']);
            foreach($cpvs as $cpv){
                if($cpv->code == $val){
                   $data[$key]['id'] = $cpv->id;
                }
            }

        }
        return $data;
    }

    public function getCpvByTenderStateId(Request $request){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $cpvs = TenderStateCpv::select(
            "cpv.code",
            "tender_state_cpv.id",
            "tender_state_cpv.estimated_price",
            "tender_state_cpv.cpv_id",
            "tender_state_cpv.view_id",
            "tender_state_cpv.cpv_name",
            "tender_state_cpv.cpv_code",
            "tender_state_cpv.unit",
            "tender_state_cpv.count",
            "tender_state_cpv.specification")
            ->leftJoin("cpv","cpv.id","=","tender_state_cpv.cpv_id")
            ->where("tender_state_id",$request->input('id'))
            ->orderBy("tender_state_cpv.id","ASC")
            ->with("statistics")->with("cpvData")->get();

        return json_decode($cpvs);
    }
    

    public function fileUploadeTranslates(CpvTranslateRequest $request){
        $service = new CpvService($request);
        $service->uploadeTranslate($request);
        return redirect("/admin/cpv");
    }

    public function searchCpvParent(Request $request){
        $data = Cpv::where("code",$request->code)->get();
        return json_decode($data);
    }

    public function manualAddCpvView(){
        return view("admin.cpv.add_cpv");
    }

    public function  manualAddCpv(Request $request){
        $parent_id = (is_null($request->input('parent_id'))) ? 0 : $request->input('parent_id');
        $unit = (is_null($request->input('unit'))) ? " " : $request->input('unit');
        $cpv = new Cpv;
        $cpv->code = $request->input('code');
        $cpv->name = $request->input('name');
        $cpv->unit = $unit;
        $cpv->type = $request->input('type');
        $cpv->parent_id = $parent_id;
        $cpv->save();

        return redirect()->back()->with('message', 'Հաջողությամբ ավելացված է');;
    }
}
