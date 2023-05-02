<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Models\Menu\Menu;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Validator;


class MenuController extends Controller{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:menu');
    }
    
    
    public function index(){
        return view('admin.menu.menu.index');
    }
    public function addEdit(Request $request, $id = false) {
    	if(!$id){
	    	$menus = new Menu();
            $url = 'admin/menu/create';
    	}else{
    		$menus = Menu::find($id);
            $url = 'admin/menu/edit/'.$id;
        }
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(),Menu::rules());
	        if ($validator->fails()) {
	            return redirect($url)
	                       ->withErrors($validator,'addEdit')
	                       ->withInput();
	        }else{
                 $menus->name    = $request->name;
         	  	 $menus->save();
	        }
	        return redirect('admin/menu');
        }
        return view('admin.menu.menu.addEdit',["menus"=>$menus]);
    }
    public function delete($id) {
        Menu::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        return Datatables::of(Menu::select('*'))
        ->addColumn('action', function ($cat) {
                     return '<a href="/admin/menu/view/'.$cat->id.'" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>Կարգավորում</a>';
                 })->editColumn('id', '{{$id}}')
        ->make(true);
    }
}
