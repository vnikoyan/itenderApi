<?php
namespace App\Http\Controllers\Api\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
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
    public function __construct(){}
   
    public function get(){
        $regulation = Regulation::first();
        return $regulation;
    }

}