<?php

namespace App\Http\Middleware\Bap;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class RoleMiddleware
 * @package App\Http\Middleware\Bap
 */
class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::guest()) {
            flash(trans('core::core.you_dont_have_required_role', ['role' => $role]))->error();
            return redirect(route('dashboard'));
        }

        $role = is_array($role)
            ? $role
            : explode('|', $role);

        if (!Auth::user()->hasAnyRole($role)) {
            if (is_array($role)) {
                $firstRole = $role[0];
            }

            flash(trans('core::core.you_dont_have_required_role', ['role' => $firstRole]))->error();
            return redirect(route('dashboard'));
        }

        return $next($request);
    }
}
