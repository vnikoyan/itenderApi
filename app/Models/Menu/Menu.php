<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model{

    protected $table = 'menu';
	
	public static function rules()
	{
		return [
				'name'   => 'required|max:50|min:2',
		];
	}

}