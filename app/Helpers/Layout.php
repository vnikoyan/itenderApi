<?php
namespace app\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class Layout{

  public static function getPermission(){
    return Permission::get()->groupBy('name');
  }
 
}