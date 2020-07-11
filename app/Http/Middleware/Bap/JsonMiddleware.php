<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 15.11.18
 * Time: 11:31
 */

namespace App\Http\Middleware\Bap;


use Closure;
use Illuminate\Http\Request;


class JsonMiddleware
{

    public function handle(Request $request, Closure $next)
    {
       $request->headers->set('Accept','application/json');

       return $next($request);
    }
}
