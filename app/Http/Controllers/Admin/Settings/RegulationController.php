<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Regulation;
use App\Models\Translation\Language;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Settings\Guide\GuideStoreAndUpdateRequest;
use Auth;

class RegulationController extends AbstractController
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
        $regulation = Regulation::first();
        return view('admin.settings.regulation.index', compact('regulation'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Regulation $guide){

        $regulation = Regulation::first();
        if(!$regulation){
            $regulation = new Regulation();
        }
        $regulation->description = $request->get('description');
        $regulation->save();
        
        return redirect()->back();
    }
    
}