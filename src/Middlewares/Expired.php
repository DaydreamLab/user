<?php

namespace  DaydreamLab\User\Middlewares;

use Closure;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::guard('api')->user();
        $token = $user->token();
        if ($token->expires_at < now()) {
            $token->delete();
            return ResponseHelper::genResponse('USER_TOKEN_EXPIRED');
        }

        if ($user->block) {
            return ResponseHelper::genResponse('USER_IS_BLOCKED');
        }

        return $next($request);
    }
}
