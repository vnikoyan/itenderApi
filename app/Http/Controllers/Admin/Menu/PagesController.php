<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Models\Menu\Pages;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use App\Models\Translation\Language;
use DB;

class PagesController extends Controller{
    
    public $leng;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:menu');
    }
    
    public function index(){
        return view('admin.menu.pages.index');
    }
    public function addEditContent(Request $request, $id = false, $leng = 'hy') {
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
            if(!$id){
             return redirect('admin/pages');
            }else{
                $pages = Pages::find($id);
                $url = 'admin/pages/edit/'.$id;
            }
            if ($request->isMethod('post')) {
              $validator = Validator::make($request->all(),['html'  => 'required|min:10']);
               if ($validator->fails()) {
                    return redirect($url)->withErrors($validator,'addEdit')->withInput();
                }else{         
                     $pages
                        ->setTranslation('html', $leng,$request->html);
                     $pages->save();
                     return redirect('admin/pages');
                }
            }
            $html = $pages->getTranslation('html', $leng);
            if(empty($html)){
                $html = $pages->getTranslation('html', "hy");
            }
            return view('admin.menu.pages.addEditContent',["pages"=>$pages,"html"=>$html,"language"=>$language,"leng"=>$leng]);
    }
    public function addEdit(Request $request, $id = false) {
        if(!$id){
            $pages = new Pages();
            $url = 'admin/pages/create';
        }else{
            $pages = Pages::find($id);
            $url = 'admin/pages/edit/'.$id;
        }

        if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(),Pages::rules());
           if ($validator->fails()) {
                return redirect($url)->withErrors($validator)->withInput();
            }else{         

                 $pages->title     = $request->title;
                 $pages->slug      = $request->slug;
                 $pages->order     = $request->order;
                 $pages->meta_title = $request->meta_title;
                 $pages->meta_description = $request->meta_description;
                 $pages->meta_key = $request->meta_key;
                 
                 $pages->save();

            }
            return redirect('admin/pages');
        }
        return view('admin.menu.pages.addEdit',["pages"=>$pages]);
    }
    public function delete($id) {
        Pages::find($id)->delete();
        return json_encode(["status"=>true]);
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        return Datatables::of(Pages::select('*')->orderBy('order'))
        ->addColumn('action', function ($cat) {
                     return '<a href="/admin/pages/edit/'.$cat->id.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>
                             <a href="/admin/pages/edit_content/'.$cat->id.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Բովանդ.</a>
                            <a href="#" data-href="/admin/pages/delete/'.$cat->id.'" class="btn btn-xs btn-danger deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->editColumn('id', '{{$id}}')
        ->make(true);
    }
     public function sortTable(Request $request){
         if ($request->isMethod('get')) {
               $sort_array = $request->sort;
               $Categorie = new Pages();
               $Categorie->sortTable($sort_array);
         }
     }   
}
