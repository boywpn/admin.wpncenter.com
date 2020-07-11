<?php

namespace App\Http\Controllers\AgentsApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\Boards\Entities\Boards;

class AgentsController extends ApiController
{
    //
    public function getBoards($request){

        $boards = Boards::where('agent_id', $this->agent['id'])
            ->with(['boardsGame' => function($query){
                $query->select('*');
            }])
            ->get();

        return $boards;

    }

}
