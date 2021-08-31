<?php

namespace  DaydreamLab\User\Middlewares;

use Closure;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class SuperUser
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
        $user = isset($request['user']) ? $request['user'] : $request['user'] = Auth::guard('api')->user();
        if (!$user || !$user->isSuperUser()) {
            return ResponseHelper::genResponse('USER_INSUFFICIENT_PERMISSION_ADMIN');
        }

        return $next($request);
    }
}
