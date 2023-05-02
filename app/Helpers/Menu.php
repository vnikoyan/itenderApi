<?php
/**
 * Created by PhpStorm.
 * User: Bee
 * Date: 02-Mar-16
 * Time: 3:57 PM
 */
namespace app\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Menu
{
    /*
    |--------------------------------------------------------------------------
    | Detect Active Route
    |--------------------------------------------------------------------------
    |
    | Compare given route with current route and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    */
    public static function isActive ($route, $output = "active") {
        if (Route::currentRouteName() == $route || (!Route::currentRouteName() && $route == "admin.index") ) {
            return $output;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Detect Active Routes
    |--------------------------------------------------------------------------
    |
    | Compare given routes with current route and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    */
    public static function areActive (Array $routes, $output = "active") {
        foreach ($routes as $route) {

            if (Route::currentRouteName() == $route) {
                return $route;
                // return $output;
            }
        }
    }

}