<?php

namespace  DaydreamLab\User\Middlewares;

use Closure;
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
        $user = $request->user('api');

        if (!$user || !$user->isSuperUser()) {
            return ResponseHelper::genResponse('InsufficientPermissionSuperAdmin', null, '', '');
        }

        return $next($request);
    }
}
