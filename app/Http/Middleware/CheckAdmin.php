<?php

namespace App\Http\Middleware;

use App\DTOs\GetUserDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = (new Controller)->checkTokenWhenReload($request, new UserService);
        if ($user != null) {
            if ($user instanceof User) {
                $roles = $user->roles->pluck('role_name')->toArray();
            } elseif ($user instanceof GetUserDTO) {
                $roles = $user->roles;
            }
            if (in_array(RoleService::ADMIN_ROLE, $roles) || in_array(RoleService::SUB_ADMIN_ROLE, $roles)) {
                return $next($request);
            }
        }
        abort(404);
    }
}
