<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserCheckStatus
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
        $flag = false;
        if(! $request->user()->status)
        {
            $message = 'Your account has been disabled. Please contact Admin in case of any concerns.';
            $flag = true;
        }
        else if(! $request->user()->is_approved) {
            $message = 'Your account is under review. Please contact Admin in case of any concerns.';
            $flag = true;
        }

        if($flag)
        {
            Auth::guard()->logout();
            return redirect()->route('login')->withErrors(['error' => $message]);
        }
        return $next($request);
    }
}
