<?php
namespace App\Http\Controllers\Admin\Cpv;

use App\Http\Controllers\Admin\AbstractController;
use App\Models\Cpv\Specifications;
use App\Models\Cpv\Cpv;
use App\Http\Requests\Itender\SpecificationsRequest;
use App\Models\Translation\Language;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\Datatables\Datatables;

class SpecificationsController extends AbstractController
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
     * @param Specifications $specifications
     * @return Renderable
     */
    public function index(Specifications $specifications){
        return view('admin.specifications.index',compact("specifications"));
    }

    /**
     * Show the application dashboard.
     *
     * @param $id
     * @return Renderable
     */
    public function getByCatId(int $id){
        $specificationsList = Specifications::where("cpv_id",$id)->with('user')->get();
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.specifications.index',compact("specificationsList",'language'));
    }

    /**
     * Show the application dashboard.
     *
     * @param $id
     * @return Renderable
     */
    public function getByCpvId(int $id){
        $specificationsList = Specifications::where("cpv_id",$id)->with('user')->get();
        return $specificationsList;
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//    */
//    public function fileUploade(CpvRequest $request){
//        $service = new CpvService($request);
//        $service->uploade();
//        return redirect("/admin/cpv");
//    }
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
     * @param SpecificationsRequest $request
     * @param Specifications $specifications
     * @return Response
     */
    public function store(SpecificationsRequest $request,Specifications $specifications){
        foreach($request->description as $key => $value){
            $specifications->setTranslation('description', $key , $request->description[$key]);
        }
        $specifications->users_id = 0;
        $specifications->cpv_id = $request->cpv_id;
        $specifications->save();

        return redirect()->back();
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
     * @param SpecificationsRequest $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id){


        $specifications = Specifications::findOrFail($id);

        if($specifications->users_id != 0 ){
            $specifications = new Specifications();
            $specifications->users_id = 0;
        }
        if($request->description){
            foreach($request->description as $key => $value){
                $specifications->setTranslation('description', $key , $request->description[$key]);
            }
        }
        if($request->cpv_id){
            $specifications->cpv_id = $request->cpv_id;
        }
        $specifications->save();
        return $specifications;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id){
        Specifications::findOrFail($id)->delete();
        return response()->json(['status' => true]);
    }

    /**
     * Process datatables ajax request.
     *
     * @param int $type
     * @return JsonResponse
     * @throws Exception
     */
    public function tableData($type=1){
        $tableData =  Datatables::of(Cpv::where("type",$type)->select('*'));
        return $tableData->editColumn('id', 'ID: {{$id}}')
        ->make(true);
    }
}
