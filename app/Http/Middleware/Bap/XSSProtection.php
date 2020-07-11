<?php

namespace App\Http\Middleware\Bap;

/**
 * XSS Protection Middleware
 *
 * Class XSSProtection
 * @package App\Http\Middleware\Bap
 */
class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (!in_array(strtolower($request->method()), ['put', 'post', 'patch'])) {
            return $next($request);
        }

        $input = $request->all();

        array_walk_recursive($input, function (&$input) {
            $input = strip_tags($input, config('bap.xss_protection_available_html_tags'));
        });

        $request->merge($input);

        return $next($request);
    }
}