<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Pages extends Model{


	use HasTranslations;
    
	public $translatable = ['html'];
	
    protected $table = 'pages';
	
	public static function rules()
	{
		return [
				'title' =>  'required|max:50|min:2',
				'slug'  =>  'required|min:2',
				'order'  => 'numeric',
		];
	}
	public function sortTable($sort_array) {
        foreach ($sort_array as $key => $sort) {
        	$sort = self::find($sort);
        	$sort->order = $key;
        	$sort->save();
        }
    }
    public static function getPageBySlug($slug){
          return self::where('slug', '=', $slug)->first()->toArray();
    }
}