<?php
/**
 * Created by PhpStorm.
 * User: Boy Developer
 * Date: 1/11/2020
 * Time: 5:24 PM
 */

namespace App\Http\Middleware;

use App\Http\Controllers\AgentsApi\ApiController;
use Closure;
use Modules\Api\Traits\RespondTrait;
use Modules\Core\Agents\Entities\Agents;
use Modules\Platform\User\Entities\User;
use Modules\Upc\Http\Controllers\UpcController;

class UpcMiddleware
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

        $data = $request->all();

        $token = $request->header('token');
        if(empty($token)){
            return response()->json($this->respondWithCode(false, 2, [], UpcController::CODEID[2]));
        }

        // Find Token
        $agent = User::where('token', $token)->first();
        if(!$agent){
            return response()->json($this->respondWithCode(false, 3, [], UpcController::CODEID[3]));
        }
        // Set Session for Agent
        $request->request->add(['entity' => $agent->toArray()]);

        return $next($request);
    }
}
