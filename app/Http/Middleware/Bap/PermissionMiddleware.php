<?php

namespace App\Http\Middleware\Bap;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class PermissionMiddleware
 * @package App\Http\Middleware\Bap
 */
class PermissionMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            flash(trans('core::core.you_dont_have_required_permission', ['permission'=>$permission]))->error();
            return redirect(route('dashboard'));
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (Auth::user()->can($permission)) {
                return $next($request);
            }
        }

        flash(trans('core::core.you_dont_have_required_permission', ['permission'=>$permission]))->error();
        return redirect(route('dashboard'));
    }
}
