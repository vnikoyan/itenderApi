<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common\Pages;

class MenuPages extends Model{

    protected $table = 'menu_page';
	
	public static function rules()
	{
		return [
				'menu_id'  => 'required|integer',
				'page_id'  => 'required|integer',
		];
	}

	public function isPageChecked($data){
        $isPageChecked = self::where('page_id', '=', $data["page_id"])->where('menu_id', '=', $data["menu_id"])->get();
        if(!empty($isPageChecked->toArray())){
        	return true;
        }else{
        	return false;
        }
    }
	public function isPageCheckedSave($data,$menu){
        $isPageChecked = self::where('menu_id', '=', $menu)->delete();
        foreach ($data as $key => $value) {
        	if($value[1] == "true"){
        		 $isPageChecked = new  MenuPages();
    		     $isPageChecked->page_id = $value[2];
    			 $isPageChecked->menu_id = $menu;
    			 $isPageChecked->save();
        	}
        }
    }    
	public function sortTable($sort_array) {
        foreach ($sort_array as $key => $sort) {
			$sort = Pages::find($sort);
			$sort->order = $key;
			$sort->save();
        }
    }

    public function getMenu($menu_id = 1){
      return self::with('getpages')->orderBy('order')->where('menu_id', '=', $menu_id)->get();
    }
    public function getpages(){
        return $this->hasOne('App\Models\Common\Pages', 'id', 'page_id');
    }
}