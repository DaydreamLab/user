<?php

namespace  DaydreamLab\User\Middlewares;

use Closure;
use DaydreamLab\JJAJ\Traits\ApiJsonResponse;

class SuperUser
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
        $user = $request->user('api');

        if (!$user || !$user->isSuperUser()) {
            return  $this->response('InsufficientPermissionSuperAdmin', null);
        }

        return $next($request);
    }
}
