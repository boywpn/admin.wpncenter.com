<?php

namespace App\Http\Middleware;

use Modules\Platform\Settings\Entities\Language;

/**
 * Class LocaleMiddleware
 * @package App\Http\Middleware
 */
class LocaleMiddleware
{

    /**
     * Switch Language
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (\Auth::user()) {
            $user = \Auth::user();

            if ($user->language != null) {
                app()->setLocale($user->language->language_key);
            } else {
                app()->setLocale(config('app.locale'));
            }
        }

        return $next($request);
    }
}
