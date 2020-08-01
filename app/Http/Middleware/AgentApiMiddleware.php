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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Modules\Api\Http\Controllers\Admin\AdminController;
use Modules\Api\Traits\RespondTrait;
use Modules\Core\Agents\Entities\Agents;

class AgentApiMiddleware
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
            return response()->json($this->respondWithCode(false, 2, [], ApiController::CODEID[2]));
        }

        // Check Action
        if(!isset($data['action'])){
            return response()->json($this->respondWithCode(false, 101, [], ApiController::CODEID[101]));
        }

        $routeName = $request->route()->getName();
        $xroute = explode(".", $routeName);
        $part = end($xroute);

        $act = $data['action'];
        if(!ApiController::checkAction($part, $act)){
            return response()->json($this->respondWithCode(false, 102, [], ApiController::CODEID[102]));
        }

        // Find Token
        $agent = Agents::where('token', $token)->first();
        if(!$agent){
            return response()->json($this->respondWithCode(false, 3, [], ApiController::CODEID[3]));
        }
        // Set Session for Agent
        $request->request->add(['entity' => $agent->toArray()]);
        $request->session()->put('entity', $agent->toArray());

        return $next($request);
    }
}
