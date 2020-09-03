<?php

namespace App\Http\Middleware;

use Closure;

class AdminCheck
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
        if(!Auth::check()){
            return response()->json([
                'msg' => 'You are not alloweed to access this route...'
            ],403);
        }
        $user = Auth::user();
        if($user->userType =='student'){
            return response()->json([
                'msg' => 'Student are not alloweed to access this route...'
            ],403);
        }
        return $next($request);
    }
}
