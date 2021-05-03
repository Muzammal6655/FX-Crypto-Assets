<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Arr;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.','token_expired' => 1], 401);
        }
        $guard = Arr::get($exception->guards(), 0);

        switch ($guard) {
          case 'admin':
            $login = 'admin.auth.login';
            break;
          default:
            $login = 'login';
            break;
        }

        if ($request->is('api/*'))
        {
            return response()->json(['status' => 0,'token_expired' => 1, 'message' => 'Unauthenticated..! You are not allowed to perform this action. Please login again to refresh your token.'], 400, ['Content-Type' => 'application/json']);
        }

        return redirect()->guest(route($login));
    }
}
