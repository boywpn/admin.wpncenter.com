<?php
/**
 * Created by PhpStorm.
 * User: Boy Developer
 * Date: 1/11/2020
 * Time: 5:24 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Traits\RespondTrait;

class EventsMiddleware
{

    use RespondTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!Auth::check()) {
            return redirect(route('login'));
        }

        return $next($request);
    }
}
