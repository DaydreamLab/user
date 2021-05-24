<?php

namespace  DaydreamLab\User\Middlewares;

use Closure;
use DaydreamLab\JJAJ\Traits\ApiJsonResponse;

class Expired
{
    use ApiJsonResponse;

    public function __construct()
    {
        $this->package = 'user';
        $this->modelName = 'User';
    }
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
                return  $this->response('TokenExpired', null);
            }

            if ($user->block) {
                return  $this->response('IsBlocked', null);
            }

            if($token->multipleLogin) {
                $token->delete();
                return  $this->response('TokenRevoked', null);
            }
        } else {
            return  $this->response('Unauthorized', null);
        }

        return $next($request);
    }
}
