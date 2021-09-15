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
        // deleted line
        
        //! $request->user()->status
        //! $request->user()->is_approved
        if ($request->user()->status != 1 ) {
            if ($request->user()->status == 0)
                $message = 'Your account has been disabled. Please contact Admin in case of any concerns.';
            else if ($request->user()->status == 2)
                $message = 'Your account is unverified. Please contact Admin in case of any concerns.';
            else
                $message = 'Your account has been deleted. Please contact Admin in case of any concerns.';
            $flag = true;
        } else if ($request->user()->is_approved != 1) {
            if ($request->user()->is_approved == 0)
                $message = 'Your account is under review. Please contact Admin in case of any concerns.';
            else
                $message = 'Your account is rejected by admin. Please contact Admin in case of any concerns.';
            $flag = true;
        }


        if ($flag) {
            Auth::guard()->logout();
            return redirect()->route('login')->withErrors(['error' => $message]);
        }
        return $next($request);
    }
}
