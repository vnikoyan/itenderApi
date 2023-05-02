<?php

// Define the namespace
namespace App\Http\Middleware;

// Include any required classes, interfaces etc...
use Closure;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;

/**
 * AuthorizeRequest Middleware
 *
 * Ensure all request are authorized
 *
 */
class AuthorizeRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @throws AppException
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Create the authorisation object
        // $shield = app('shield');
        $shield = auth('api');
        // Determine whether the user is authorised
        if(!$shield->user()){
            return $shield->user();
            throw new AppException(AppExceptionType::$UNAUTHORIZED);
        } else if($shield->user()->status === 'BLOCK'){
            throw new AppException(AppExceptionType::$BLOCKED);
        } else {
            return $next($request);
        }
        
    }
}