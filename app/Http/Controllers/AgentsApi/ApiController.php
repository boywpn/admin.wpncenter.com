<?php

namespace App\Http\Controllers\AgentsApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Api\Traits\RespondTrait;
use Modules\Platform\Core\Repositories\GenericRepository;

class ApiController extends Controller
{
    //

    use RespondTrait;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    protected $entityClass;

    protected $datetime;

    protected $agent;
    protected $agent_id;
    protected $username_length = 13;

    const CODEID = [
        '0' => 'Success',
        '1' => 'Access Denied.',
        '2' => 'The token is required.',
        '3' => 'The token invalid.',

        '101' => 'The action is required.',
        '102' => 'The action is invalid.',

        '201' => 'Invalid request data.',
        '202' => 'Transfer not success.',

        '301' => 'Data not match.'
    ];

    const ACTION = [
        'agent' => [
            'getBoards'
        ],
        'member' => [
            'regisUsername',
            'transferCredit',
            'login',
            'checkBalance',
            'changePassword'
        ]
    ];

    /**
     * Module Repository
     * @var
     */
    protected $repository = GenericRepository::class;

    public function __construct(Request $request)
    {
        $this->datetime = date('Y-m-d H:i:s');
    }

    public static function checkAction($part, $action){

        if(!isset(self::ACTION[$part])){
            return false;
        }

        if(!in_array($action, self::ACTION[$part])){
            return false;
        }

        return true;
    }

    public function index(){

        return $this->error(1);

    }

    public function action(Request $request){

        $data =  $request->all();

        $this->agent = $data['entity'];
        $this->agent_id = $data['entity']['id'];

        $act = $data['action'];

        return $this->{$act}($data);

    }

    /**
     * Create instance of module repository or use generic repository
     *
     * @return mixed
     */
    protected function getRepository()
    {
        if ($this->repository == GenericRepository::class) {
            $repository = \App::make($this->repository);
            $repository->setupModel($this->entityClass);
        } else {
            $repository = \App::make($this->repository);
        }

        return $repository;
    }

    public function error($codeid, $data = [], $msg = null, $status = null)
    {
        if(empty($msg)) {
            $msg = self::CODEID[$codeid];
        }
        return response()->json($this->respondWithCode(false, $codeid, $data, $msg), 400);
    }
    public function errorMsg($codeid, $data = [], $status = null)
    {
        $msg = self::CODEID[$codeid];
        return $this->respondWithCode(false, $codeid, $data, $msg);
    }

    public function success($codeid, $data = [], $msg = null)
    {
        if(empty($msg)) {
            $msg = self::CODEID[$codeid];
        }
        return response()->json($this->respondWithCode(true, $codeid, $data, $msg), 200);
    }
    public function successMsg($codeid, $data = [])
    {
        $msg = self::CODEID[$codeid];
        return $this->respondWithCode(true, $codeid, $data, $msg);
    }

}
