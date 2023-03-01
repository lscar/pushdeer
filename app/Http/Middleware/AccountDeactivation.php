<?php

namespace App\Http\Middleware;

use App\Http\ReturnCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AccountDeactivation
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::getUser();
        if ($user->level < 1) {
            return Response::error("账号已被禁用", ReturnCode::AUTH);
        }

        return $next($request);
    }
}
