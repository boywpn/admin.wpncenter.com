<?php
/**
 * Created by PhpStorm.
 * User: Boy Developer
 * Date: 1/11/2020
 * Time: 5:24 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Modules\Api\Http\Controllers\Admin\AdminController;
use Modules\Api\Traits\RespondTrait;

class AdminMiddleware
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

        $act = $request->input('act');
        if(!in_array($act, AdminController::ACTION)){
            return response()->json($this->respondWithCode(false, 1001, [], AdminController::CODEID[1001]), 400);
        }

        return $next($request);
    }
}
