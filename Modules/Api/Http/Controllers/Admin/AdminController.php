<?php

namespace Modules\Api\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Traits\RespondTrait;
use Modules\Platform\Core\Repositories\GenericRepository;
use phpDocumentor\Reflection\Types\Self_;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class AdminController extends Controller
{

    use RespondTrait;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    protected $entityClass;

    public $datetime;

    const CODEID = [
        '0' => 'Success',
        '1' => 'Access Denied',
        '2' => 'Invalid Token',

        '1001' => 'Invalid Action',

        '2001' => 'Invalid Values',
        '2002' => 'Cannot identified id',

        '3001' => 'Data not match.'
    ];

    const WALLET_MATHOD = [
        '1' => 'Statement to Wallet',
        '2' => 'Wallet to Game',
        '3' => 'Game to Wallet',
        '4' => 'Wallet to Bank',
        '5' => 'Event to Wallet',
        '6' => 'Void to Wallet',
        '7' => 'Void from Cancel',
    ];

    const ACTION = [

        'lists',
        'insert',
        'view',
        'update',

        'getPartners',
        'editPartner',

        'getAgents',
        'editAgent',

        'getMembers',
        'editMember',

        'getUsernames',
        'getUsernamesByMember',
    ];

    /**
     * Module Repository
     * @var
     */
    protected $repository = GenericRepository::class;

    public function __construct(Request $request)
    {
        $this->datetime = date('Y-m-d H:i:s');
        $this->middleware('jwt.auth');

    }

    public function index(){

        return $this->error(1);

    }

    public function action(Request $request){

        $act =  $request->input('act');

        $input = $request->except('act');

        return $this->{$act}($input);

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

    public function error($codeid, $data = [], $status = null)
    {
        $msg = self::CODEID[$codeid];
        return response()->json($this->respondWithCode(false, $codeid, $data, $msg), 400);
    }
    public function errorMsg($codeid, $data = [], $status = null)
    {
        $msg = self::CODEID[$codeid];
        return $this->respondWithCode(false, $codeid, $data, $msg);
    }

    public function success($codeid, $data = [])
    {
        $msg = self::CODEID[$codeid];
        return response()->json($this->respondWithCode(true, $codeid, $data, $msg), 200);
    }
    public function successMsg($codeid, $data = [])
    {
        $msg = self::CODEID[$codeid];
        return $this->respondWithCode(true, $codeid, $data, $msg);
    }

    public function view($request){

        $repository = $this->getRepository();

        $validator = Validator::make($request, [
            'id' => 'required|integer'
        ]);

        if($validator->fails()){
            return $this->error(2001, $validator->errors());
        }

        $partner = $repository->findWithoutFail($request['id']);

        if(!$partner){
            return $this->error(3001, $validator->errors());
        }

        return $this->success(0, $partner);

    }

    public function update($request){

        $repository = $this->getRepository();

        $validator = Validator::make($request, [
            'id' => 'required|integer'
        ]);

        $id = $request['id'];
        $entity = $repository->find($id);

        if($validator->fails()){
            return $this->error(2001, $validator->errors());
        }

        unset($request['id']);

        $entity = $repository->updateEntity($request, $entity);

        return $entity;

    }
}