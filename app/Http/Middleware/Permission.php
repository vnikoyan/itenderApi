<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        return $next($request);
        if (Auth::guard('admin')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if(Auth::guard('admin')->user()->id == 1){
            return $next($request);
        }
        $permissions = is_array($permission)
        ? $permission
        : explode('|', $permission);
        foreach ($permissions as $permission) {
            if (Auth::guard('admin')->user()->can($permission)) {
                return $next($request);
            }
        }
        throw UnauthorizedException::forPermissions($permissions);
     
    }
}
