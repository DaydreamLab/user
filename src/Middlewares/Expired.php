<?php

namespace  DaydreamLab\User\Middlewares;

use Closure;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Expired
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

        if ($user) {
            $token = $user->token();
            if ($token->expires_at < now()) {
                $token->delete();
                return ResponseHelper::genResponse(
                    Str::upper(Str::snake('TokenExpired')),
                    null,
                    'User',
                    'User'
                );
            }

            if ($user->block) {
                return ResponseHelper::genResponse(
                    Str::upper(Str::snake('IsBlocked')),
                    null,
                    'User',
                    'User'
                );
            }

            if($token->multipleLogin) {
                $token->delete();
                return ResponseHelper::genResponse(
                    Str::upper(Str::snake('TokenRevoked')),
                    null,
                    'User',
                    'User'
                );
            }
        } else {
            return ResponseHelper::genResponse(
                Str::upper(Str::snake('Unauthorized')),
                null,
                '',
                ''
            );
        }

        return $next($request);
    }
}
