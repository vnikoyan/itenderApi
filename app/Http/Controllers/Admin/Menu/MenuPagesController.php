<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Models\Menu\Pages;
use App\Models\Menu\MenuPages;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Yajra\Datatables\Datatables;

class MenuPagesController extends Controller{

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:menu');
    }
    public function view($id){
        $page = Pages::orderBy('order')->get();
        $menuPages = new MenuPages();
        return view('admin.menu.menuPages.index',["page"=>$page,"menuPages"=>$menuPages,"id"=> $id]);
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData(){
        return Datatables::of(MenuPages::select('*')->orderBy('order'))
        ->addColumn('action', function ($cat) {
                     return '<a href="/admin/menuPages/edit/'.$cat->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a><a href="/admin/menuPages/delete/'.$cat->id.'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> delete</a>';
                 })->editColumn('id', '{{$id}}')
        ->make(true);
    }
     public function sortTable(Request $request){
         if ($request->isMethod('get')) {
               $sort_array = $request->sort;
               $Categorie = new MenuPages();
               $Categorie->sortTable($sort_array);
         }
     }        
     public function isPageCheckedSave(Request $request){
         if ($request->isMethod('get')) {
               $isPageCheckedSave = $request->isChecked;
               $menu = $request->menu;
               $menuPages = new MenuPages();
               $menuPages->isPageCheckedSave($isPageCheckedSave,$menu);
         }
     }   
}
