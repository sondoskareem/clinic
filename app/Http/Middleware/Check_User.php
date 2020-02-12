<?php

namespace App\Http\Middleware;

use Closure;

class Check_User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth('api')->user()->isTrusted =='false') {
            return  response()->json(['error' => 'Unauthorize' ,'code' => 401]);
        }

        return $next($request);
    }
}
