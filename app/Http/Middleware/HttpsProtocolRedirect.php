<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class HttpsProtocolRedirect {

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && App::environment() === 'production') {
            return redirect()->secure($request->getRequestUri());
        }

        if (strpos($request->getRequestUri(), '/public') !== false) {
            $uri = url($request->getRequestUri());
            $uri = str_replace('/public','',$uri);
            return redirect()->to($uri);
        }

        return $next($request); 
    }
}

?>