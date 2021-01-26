<?php

namespace  DaydreamLab\User\Middlewares;

use Closure;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Str;

class Admin
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
        $user = isset($request['user'])
            ? $request['user']
            : $request->user('api');

        if (!$user || !$user->isAdmin()) {
            return ResponseHelper::genResponse(
                Str::upper(Str::snake('InsufficientPermissionAdministrator')),
                null,
                '',
                ''
            );
        }

        return $next($request);
    }
}
