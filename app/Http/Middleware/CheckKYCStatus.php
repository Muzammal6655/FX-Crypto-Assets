<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckKYCStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!CheckKYCStatus())
        {
            return redirect('/documents');
        }
        return $next($request);
    }
}
