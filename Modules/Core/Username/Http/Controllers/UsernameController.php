<?php

namespace Modules\Core\Username\Http\Controllers;

use App\Http\Controllers\Games\Dg\MemberController AS DG;
use App\Http\Controllers\Games\Og\MemberController AS OG;
use App\Http\Controllers\Games\Sa\MemberController AS SA;
use App\Http\Controllers\Games\Aec\MemberController AS AEC;
use App\Http\Controllers\Games\Sexy\MemberController AS SEXY;
use App\Http\Controllers\Games\Ibc\MemberController AS IBC;
use App\Http\Controllers\Games\Sbo\MemberController AS SBO;
use App\Http\Controllers\Games\Lotto\MemberController AS LOTTO;
use App\Http\Controllers\Games\Tiger\MemberController AS TIGER;
use App\Http\Controllers\Games\Csh\MemberController AS CSH;
use App\Http\Controllers\Games\Pussy\MemberController AS PUSSY;
use App\Http\Controllers\Games\Tfg\MemberController AS TFG;
use App\Http\Controllers\Games\Trnf\MemberController AS Trnf;
use App\Http\Requests\Request;
use App\Models\Old\Members;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Datatables\UsernameDatatable;
use Modules\Core\Username\Entities\Username;
use Modules\Core\Username\Entities\UsernameBalance;
use Modules\Core\Username\Http\Forms\UsernameForm;
use Modules\Core\Username\Http\Requests\UsernameRequest;
use Modules\Member\Members\Entities\MembersUsername;
use Modules\Member\Members\Http\Controllers\MembersController;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Traits\APITrait;
use Modules\Platform\User\Entities\User;

class UsernameController extends ModuleCrudController
{

    use APITrait;

    protected $datatable = UsernameDatatable::class;
    protected $formClass = UsernameForm::class;
    protected $storeRequest = UsernameRequest::class;
    protected $updateRequest = UsernameRequest::class;
    protected $entityClass = Username::class;

    protected $moduleName = 'CoreUsername';
    protected $moduleAlias = 'core/username';

    protected $cssFiles = [

    ];

    protected $jsFiles = [
        // 'username.js'
    ];

    protected $permissions = [
        'browse' => 'core.username.browse',
        'create' => 'core.username.create',
        'update' => 'core.username.update',
        'destroy' => 'core.username.destroy'
    ];

    protected function setupCustomButtons()
    {
        //if(!empty($this->entity->member_id) || $this->entity->code == '000') {
            $this->customShowButtons[] = array(
                'href' => route('core.username.push_username', $this->entity->id),
                'attr' => [
                    'class' => 'btn bg-pink waves-effect pull-right'
                ],
                'label' => trans('core/username::username.push_username_to_provider')
            );
        //}
    }

    protected $relationTabs = [
        'betlimit' => [
            'icon' => 'donut_small',
            'permissions' => [
                'browse' => 'core.username.betlimit'
            ],
            'custom' => [
                'view' => 'core/username::betlimit',
                'database' => Username::class
            ]
        ],
    ];

    protected $showFields = [
        'login' => [
            'board_id' => ['type' => 'manyToOne', 'relation' => 'usernameBoard', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'code' => [
                'type' => 'text',
                'col-class' => 'col-lg-3'
            ],
            'username' => [
                'type' => 'username',
                'col-class' => 'col-lg-3',
                'hide_in_form' => true
            ],
            'password' => [
                'type' => 'password_username',
                'col-class' => 'col-lg-3'
            ]
        ],

        'details' => [
            'member_id' => ['type' => 'manyToOne', 'relation' => 'usernameMember', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'is_active' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],
            // 'bet_limits' => ['type' => 'betLimits', 'col-class' => 'col-lg-3'],
            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'core/username::username';

    protected $routes = [
        'index' => 'core.username.index',
        'create' => 'core.username.create',
        'show' => 'core.username.show',
        'edit' => 'core.username.edit',
        'store' => 'core.username.store',
        'destroy' => 'core.username.destroy',
        'update' => 'core.username.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Before entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function beforeStore($request)
    {

        $board = Boards::find($request->board_id);
        $username = $board->member_prefix . $request->code;

        // Check Member
        $count = Username::where('board_id', '=', $request->board_id)
            ->where('username', '=', $username)->count();

        if($count > 0){
            $data = [
                'status' => false,
                'message' => trans('core/username::username.have_exist'),
                'route' => $this->routes['create']
            ];
            return $data;
        }

        $password = Crypt::encryptString($request->get('password'));
        $data = [
            'replace' => [
                'password' => $password,
                'username' => $username
            ],
        ];

        return $data;

    }

    public function beforeStoreInput($request, &$input)
    {

        $input['bet_limits'] = json_encode($request->bet_limits);

    }

    /**
     *
     * @param $request
     * @param $entity
     * @param $input
     */
    public function beforeUpdate($request, &$entity, &$input)
    {

        $board = Boards::find($request->board_id);
        $username = $board->member_prefix . $request->code;

        $input['bet_limits'] = json_encode($request->bet_limits);

        if($entity->code != $input['code']) {
            // Check Member
            $count = Username::where('board_id', '=', $request->board_id)
                ->where('username', '=', $username)->count();

            if ($count > 0) {
                $data = [
                    'status' => false,
                    'message' => trans('core/username::username.have_exist'),
                    'route' => route($this->routes['edit'], $entity)
                ];
                return $data;
            }

            $input['username'] = $username;
        }

        if (isset($input['password'])) {
            if($entity->password != $input['password']) {
                $input['password'] = Crypt::encryptString($request->get('password'));
            }else{
                unset($input['password']);
            }
        }else{
            unset($input['password']);
        }
    }

    /**
     * For Generate by Manual
    */
    public function genUsernameAuto(){

        $usernames = Username::where('is_active', 1)
            ->where('board_id', 4)
            ->where('is_created', 0)
            //->limit(2)
            ->get();

        foreach ($usernames as $username){

            echo $username->id."\r\n";

            $this->pushUsername($username->id);

        }

    }

    public function pushUsername($id){

        $response = $this->pushUsernameAction($id);

        $repository = $this->getRepository();
        $repository->setupModel(Username::class);
        $entity_username = $repository->find($id);

        // $repository->updateEntity(['is_created' => 1, 'is_created_at' => date('Y-m-d H:i:s')], $entity_username);

        if($response['status']){
            // update
            $repository->updateEntity(['is_created' => 1, 'is_created_at' => date('Y-m-d H:i:s')], $entity_username);

            flash(trans('core/username::username.created_success')." - ".$response['message']['message'])->success();
        }else{
            flash($response['message']['message'])->error();
        }

        return redirect()->route($this->routes['show'], $id);

    }

    public function pushUsernameApi($id){

        $response = $this->pushUsernameAction($id);

        $repository = $this->getRepository();
        $repository->setupModel(Username::class);
        $entity_username = $repository->find($id);

        if($response['status']){
            // update
            $repository->updateEntity(['is_created' => 1, 'is_created_at' => date('Y-m-d H:i:s')], $entity_username);
        }

        return $response;

    }

    public function getUsernameByID($id){

        $username = Username::where('id', $id)
            ->with(['usernameBoard' => function($query){
                $query->select('*');
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select('*');
            }, 'usernameBoard.boardsPartner' => function($query){
                $query->select('*');
            }, 'usernameBoard.usersBoard' => function($query){
                $query->select('*')->inRandomOrder()->first();
            }])
            ->first()
            ->toArray();

        return $username;

    }

    public function pushUsernameAction($id){

        $username = $this->getUsernameByID($id);

        if(!isset($username['username_board']['boards_game']['code'])){
            Artisan::call('cache:clear');
            // Call Check Again
            $username = $this->getUsernameByID($id);
        }

        $user = $username['username'];
        $password = Crypt::decryptString($username['password']);
        $key = json_decode($username['username_board']['api_code'], true);

        if($username['username_board']['boards_game']['code'] == "sa"){

            $api = new SA($key);

            // check exist
            $setParam = [
                'method' => 'VerifyUsername',
                'Username' => $user,
                'CurrencyType' => 'THB'
            ];
            $api->setParam($setParam);
            $response = $api->push();
            $res = xmlDecode($response, true);
            if($res['IsExist'] == 'true'){
                return respond(false, [], ['error' => 'username_is_exist'], ['message' => trans('core/username::username.username_is_exist')]);
            }

            // create username
            $setParam = [
                'method' => 'RegUserInfo',
                'Username' => $user,
                'CurrencyType' => 'THB'
            ];
            $api->setParam($setParam);
            $response = $api->push();
            $res = xmlDecode($response, true);

            if($res['ErrorMsgId'] == 0){

                // Set Bet Limit Default
                $setParam = array(
                    'method' => 'SetBetLimit',
                    'Username' => $user,
                    'Currency' => 'THB',
                    'Set1' => 2199023255552, // 5-3000
                    'Set2' => 17592186044416 // 20-5000
//                    'Set1' => 1,
//                    'Set2' => 32,
//                    'Set3' => 2048,
//                    'Set4' => 1048576,
//                    'Set5' => 4194304
                );
                $api->setParam($setParam);
                $response = $api->push();

                $res = xmlDecode($response, true);
//                print_r($res);
//
//                exit;

                return respond(true, [], ['error' => 'username_create_success'], ['message' => $res['ErrorMsg']]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $res['ErrorMsg']]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "og"){

            $api = new OG($key);
            $api->setToken();

            $setParam = [
                "username" => $user,
                "country" => "Thai",
                "fullname" => $username['username_board']['member_prefix']." ".$username['code'],
                "language" => "en",
                "email" => $user."@".strtolower($username['username_board']['boards_partner']['name']).".og",
                "birthdate" => date('Y-m-d')
            ];
            $api->setParam($setParam, 'register', true);
            $response = $api->push(true);

            if($response['status'] == 'success'){
                return respond(true, [], ['error' => 'username_create_success'], ['message' => 'Success']);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['data']['message']]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "dg"){

            $api = new DG($key);

            $setParam = [
                'member' => [
                    "username" => $user,
                    "password" => md5(Crypt::decryptString($username['password'])),
                    "currencyName" => "THB",
                    "winLimit" => 0
                ]
            ];
            $api->setParam($setParam, 'user/signup');
            $response = $api->push();

            if($response['codeId'] == 0){

                // Set Red Limit
                $setParam = [
                    'data' => 'L',
                    'member' => [
                        'username' => $user
                    ]
                ];
                $api->setParam($setParam, 'game/updateLimit');
                $response = $api->push();

                return respond(true, [], ['error' => 'username_create_success'], ['message' => $api->getCode($response['codeId'])]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $api->getCode($response['codeId'])]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "aec"){

            $api = new AEC($key);

            $dataCreate = [
                "username" => $user
            ];
            $response =$api->saveUsername($dataCreate);

            if($response['error'] == 0){

                /**
                 * Change Bet Limit
                 **/
                $setParam = [
                    'userName' => $user,
                    'Act' => 'MB_BET_SETTING',
                    'min' => '5',
                    'max' => '50000',
                    'maxPerMatch' => '50000',
                ];
                $res = $api->actionPost($setParam);

                return respond(true, [], ['error' => 'username_create_success'], ['message' => $api->getCode($response['error'])]);

            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $api->getCode(1)]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "sexy"){

            $api = new SEXY($key);

            $dataCreate = [
                "username" => $user
            ];

            $response =$api->saveUsername($dataCreate);

            if($response['status'] == 0000){
                return respond(true, [], ['error' => 'username_create_success'], ['message' => $api->getCode($response['status'])]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $api->getCode($response['status'])]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "ibc"){

            $api = new IBC($key);

            $dataCreate = [
                "username" => $user,
                "password" => $password
            ];

            $response =$api->saveUsername($dataCreate);

            // return compact('key', 'response');

            if($response['errCode'] == 0){
                return respond(true, [], ['error' => 'username_create_success'], ['message' => $response['errMsg']]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['errMsg']]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "lottosh"){

            $api = new LOTTO($key);

            $dataCreate = [
                "username" => $user,
            ];

            $response = $api->saveUsername($dataCreate);

            if($response['status'] == 'success'){
                return respond(true, [], ['error' => 'username_create_success'], ['message' => $response['msg']]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['msg']]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "tiger"){

            $api = new TIGER($key);

            $user = str_replace($username['username_board']['ref'], '', $user);

            $dataCreate = [
                "username" => $user,
                "password" => $password,
            ];

            $response = $api->saveUsername($dataCreate);

            if($response['errcode'] == '00070'){
                return respond(true, [], ['error' => 'username_create_success'], ['message' => $response['errtext']]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['errtext']]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "sboapi"){

            $api = new SBO($key);

            $setParam = [
                "username" => $user
            ];
            $response = $api->saveUsername($setParam);

            if($response['error']['id'] == 0){
                return respond(true, [], ['error' => 'username_create_success'], ['message' => $response['error']['msg']]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['error']['msg']]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "csh"){

            $api = new CSH($key);

            $user = str_replace($username['username_board']['ref'], '', $user);

            $setParam = [
                "username" => $user,
                "password" => $password
            ];

            $response = $api->saveUsername($setParam);

            if($response['status'] != 'error'){
                // Update Ref ID
                Username::where('id', $username['id'])->update(['ref_id' => $response['id']]);

                return respond(true, [], ['error' => 'username_create_success'], ['message' => $response['msg']]);
            }else{
                if(isset($response['id'])) {
                    // Update Ref ID
                    Username::where('id', $username['id'])->update(['ref_id' => $response['id']]);
                }

                return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['msg']]);
            }

        }
        elseif($username['username_board']['boards_game']['code'] == "tfg"){

            $api = new TFG($key);

            // Created Username When Deposit
            return respond(true, [], ['error' => 'username_create_success'], ['message' => 'ระบบจะสร้างหลังจากเติมเครดิตครั้งแรก']);

        }
        elseif($username['username_board']['boards_game']['code'] == "pussy"){

            $api = new PUSSY($key);

            if(!empty($username['ref_id'])){

                $password = rand12(8, 'Aa+');
                $new_pass = Crypt::encryptString($password);
                // Update Ref ID
                Username::where('id', $username['id'])->update(['password' => $new_pass]);

                $username = $username['ref_id'];
                $account = $username;

                // addPlayer
                $setParam = [
                    "agent" => $api->agent,
                    "account" => $account,
                    "username" => $user,
                    "password" => $password,
                ];
                $response = $api->addUser($setParam);

                if($response['code'] == 0) {
                    return respond(true, [], ['error' => 'username_create_success'], ['message' => 'Created Username Success with Username: ' . $account . ' Password: ' . $password]);
                }else{
                    return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['msg']]);
                }

            }else{ // If exist account

                $password = rand12(8, 'Aa+');

                $setParam = [
                    "agent" => $api->agent,
                    "username" => $user,
                    "password" => $password,
                ];
                $response = $api->randomUsername($setParam);

                if($response['code'] == 0){
                    $new_pass = Crypt::encryptString($password);
                    // Update Ref ID
                    Username::where('id', $username['id'])->update(['ref_id' => $response['account'], 'password' => $new_pass]);
                    $account = $response['account'];

                    // addPlayer
                    $setParam = [
                        "agent" => $api->agent,
                        "account" => $account,
                        "username" => $user,
                        "password" => $password,
                    ];
                    $response = $api->addUser($setParam);

                    if($response['code'] == 0) {
                        return respond(true, [], ['error' => 'username_create_success'], ['message' => 'Created Username Success with Username <b>' . $account . '</b> Password <b>' . $password . '</b>']);
                    }else{
                        return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['msg']]);
                    }
                }else{
                    return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['msg']]);
                }

            }

        }
        elseif($username['username_board']['boards_game']['code'] == "sbo"){

            $api = new SBO($key);

            $setParam = [
                "username" => $user,
                "language" => "th-th"
            ];
            $api->setParam($setParam, 'player/register-player.aspx');
            $response = $api->push();

            $response = json_decode($response, true);

            if($response['error']['id'] == 0){
                return respond(true, [], ['error' => 'username_create_success'], ['message' => $api->getCode($response['error']['id']) ]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $api->getCode($response['error']['id']) ]);
            }


        }

        /**
         * For credit website
        */
        elseif(in_array($username['username_board']['boards_game']['code'], ['ufa', 'lga', 'gcb', 'vga'])){

            $api = new Trnf($key);

            $code = $username['username_board']['boards_game']['code'];
            $ag_data = $username['username_board']['users_board'][0];
            $api->setAgentUser($ag_data);

//            return $username;
            $user_create = $username['code'];
            if($code == 'gcb'){
                $user_create = $username['username_board']['board_number'].$username['code'];
            }

            $setParam = [
                "username" => $user_create,
                "username_login" => $user,
                'password' => $password,
                "game" => $username['username_board']['boards_game']['code']
            ];
            $response = $api->saveUsername($setParam);

            if($response['responseStatus']['code'] == 200){
                if(isset($response['responseStatus']['pass_status'])){
                    if($response['responseStatus']['pass_status']){
                        $new_pass = $password."+";
                        $new_pass = Crypt::encryptString($new_pass);
                        Username::where('id', $username['id'])->update(['password' => $new_pass]);
                    }
                }
                return respond(true, [], ['error' => 'username_create_success'], ['message' => $response['responseStatus']['messageDetails'] ]);
            }else{
                return respond(false, [], ['error' => 'username_create_error'], ['message' => $response['responseStatus']['messageDetails'] ]);
            }


        }

    }

    public function balanceByBoard($entity_id){

        $users = Boards::where('id', $entity_id)
            ->with(['membersBoard' => function($query){
                $query->where('is_created', 1)->select('id', 'board_id', 'username');
            }, 'boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($users['api_code'], true);

        $repository = $this->getRepository();

        $data = [];
        foreach ($users['members_board'] as $user){

            if($users['boards_game']['code'] == "aec"){

                $api = new AEC($key);

                $username = $user['username'];
                $res = null;

                /**
                 * Get Balance
                 **/
//                $setParam = [
//                    'userName' => $user['username'],
//                    'Act' => 'MB_GET_BALANCE',
//                ];
//                $res = $api->actionPost($setParam);

                /**
                 * Change Bet Limit
                 **/
                $setParam = [
                    'userName' => $user['username'],
                    'Act' => 'MB_BET_SETTING',
                    'min' => '5',
                    'max' => '50000',
                    'maxPerMatch' => '50000',
                ];
//                $res = $api->actionPost($setParam);

                /**
                 * Suspend
                 **/
//                $setParam = [
//                    'userName' => $user['username'],
//                    'Act' => 'MB_UPD_STATUS',
//                    'status' => '1',
//                ];
//                $res = $api->actionPost($setParam);

//                $arrData = [
//                    'username_id' => $user['id'],
//                    'username' => $user['username'],
//                    'balance' => $res['balance'],
//                ];
//
//                $balance = $res['balance'];
//                $amount = 0 - $balance;
//                $amount = number_format($amount, 2, '.', '');
//
//                $setParam = [
//                    'userName' => $user['username'],
//                    'amount' => $amount,
//                    'remark' => (($amount > 0) ? 'IN' : 'OUT') . date('YmdHis') . $user['username'],
//                ];
//                if($amount >= 0){
//                    $setParam['Act'] = "MB_DEPOSIT";
//                }else{
//                    $setParam['amount'] = +$amount * -1;
//                    $setParam['Act'] = "MB_WITHDRAW";
//                }
//
//                $res = null;
//                if($setParam['amount'] > 1){
//                    $res = $api->actionPost($setParam);
//
//                    $arrData['setParam'] = $setParam;
//                    $arrData['Res'] = $res;
//
//                    $data[] = $arrData;
//                }

                $arrData['setParam'] = $setParam;
                $arrData['Res'] = $res;
                $data[] = $arrData;




                // $repository->createEntity($arrData, \App::make(UsernameBalance::class));

            }

        }

        return $data;

    }

    public function betLimitByBoard($entity_id){

        $users = Boards::where('id', $entity_id)
            ->with(['membersBoard' => function($query){
                $query->where('is_created', 1)->select('id', 'board_id', 'username');
            }, 'boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($users['api_code'], true);

        $repository = $this->getRepository();

        $data = [];
        foreach ($users['members_board'] as $user){

            if($users['boards_game']['code'] == "sa"){

                $api = new SA($key);

                // Set Bet Limit Default
                $setParam = array(
                    'method' => 'SetBetLimit',
                    'Username' => $user['username'],
                    'Currency' => 'THB',
                    'Set1' => 1, // 5-500
                );
                $api->setParam($setParam);
                $response = $api->push();

                $res = xmlDecode($response, true);

                $data[] = compact('setParam', 'res');

            }

        }

        return $data;

    }

    public function setBetLimit($entity_id, \Illuminate\Http\Request $request){

        $post = $request->all();

        $repository = $this->getRepository();
        $entity = $repository->find($entity_id);

        $user = Username::where('id', $entity_id)
            ->with(['usernameBoard' => function($query){
                $query->select('id', 'api_code', 'ref', 'name', 'game_id');
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select('*');
            }])
            ->first();

        $user = $user->toArray();

        $key = json_decode($user['username_board']['api_code'], true);

        if($user['username_board']['boards_game']['code'] == "csh") {

            $api = new CSH($key);
            $setData = [
                'ref_id' => $user['ref_id'],
                'limits' => $post['bet_limit']
            ];

            $response = $api->setBetLimit($setData);

        }

        $bet_limit = json_encode($post['bet_limit']);
        $repository->updateEntity(['bet_limits' => $bet_limit], $entity);


        flash(trans('core/username::username.bet_limit_success'))->success();
        return redirect()->route($this->routes['show'], $entity_id);

    }

    /**
     * Created Username
    */
    public function createByBoard($entity_id){

        $users = Boards::where('id', $entity_id)
            ->with(['membersBoard' => function($query){
                $query->select('id', 'board_id', 'username', 'password')->where('is_created', 0);
            }, 'boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($users['api_code'], true);

//        return $users;

        $repository = $this->getRepository();

        $data = [];
        foreach ($users['members_board'] as $user){

            if($users['boards_game']['code'] == "csh") {

                // Update Ref ID

                $api = new CSH($key);

                $username = str_replace($key['agent'], '', $user['username']);
                $password = Crypt::decryptString($user['password']);

                $setParam = [
                    "username" => $username,
                    "password" => $password
                ];
                $response = $api->saveUsername($setParam);

                // Update Ref ID
                Username::where('id', $user['id'])->update(['ref_id' => $response['id'], 'is_created' => 1, 'is_created_at' => date('Y-m-d H:i:d')]);

                $data[] = [
                    'user' => $user,
                    'response' => $response,
                    'username' => $username,
                    'password' => $password
                ];

            }

        }

        return $data;

    }

    /**
     * Set Username Event
     */
    public function setUserEvByBoard($entity_id){

        $users = Boards::where('id', $entity_id)
            ->with(['membersBoard' => function($query){
                $query->select('id', 'board_id', 'username', 'password');
            }, 'boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($users['api_code'], true);

        $repository = $this->getRepository();

        $data = [];
        foreach ($users['members_board'] as $user){

            if($users['boards_game']['code'] == "csh") {

                $pass = rand12(6);
                $username = $entity_id.str_replace('b11', '', $user['username']);
                $password = Crypt::encryptString($pass);

                $data[] = [
                    'username' => $username,
                    'password' => $pass
                ];

                Username::where('id', $user['id'])->update(['ev_username' => $username, 'ev_password' => $password]);
            }

        }

        return $data;

    }

    /**
     * Disable Username Event
     */
    public function disableUserEvByBoard($entity_id, $status){

        $users = Boards::where('id', $entity_id)
            ->with(['membersBoard' => function($query){
                $query->select('id', 'board_id', 'username', 'password');
            }, 'boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($users['api_code'], true);

        $repository = $this->getRepository();

        $data = [];
        foreach ($users['members_board'] as $user){

            if($users['boards_game']['code'] == "csh") {
                Username::where('id', $user['id'])->update(['ev_status' => $status]);
            }

        }

    }

    /**
     * Disable Username Event
     */
    public function disableUserEvByID($entity_id, $status){

        Username::where('id', $entity_id)->update(['ev_status' => $status]);
        return response()->json(['status'=>true, 'msg'=>'เปลี่ยนสถานะเรียบร้อย'], 200);

    }

    /**
     * Transfer Credit
    */
    public function topupEvent($entity_id){

        if($entity_id != 81){
            return "Access Deny!";
        }

        $users = Boards::where('id', $entity_id)
            ->with(['membersBoard' => function($query){
                $query->select('id', 'ref_id', 'board_id', 'username', 'password');
            }, 'boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($users['api_code'], true);

        $repository = $this->getRepository();

        $data = [];
        foreach ($users['members_board'] as $user){
            $amount = 100;

            if($users['boards_game']['code'] == "csh") {

                $api = new CSH($key);

                $arrUsername = [
                    'id' => $user['ref_id']
                ];
                $balance = $api->actionPost($arrUsername, 'balance');

                $amount = $amount - $balance['balance'];

                $response = [];
                if($amount != 0){
                    $setParam = [
                        'id' => $user['ref_id'],
                        'amount' => $amount
                    ];
                    if($amount > 0){
                        $url = "credit_p";
                        $ref = "INEV".date('YmdHis').$user['username'];
                    }else{
                        $url = "credit_p";
                        $ref = "OUTEV".date('YmdHis').$user['username'];
                    }
                    $setParam['refID'] = $ref;

                    $response = $api->actionPost($setParam, $url);
                }

                $data[] = [
                    'user' => $user,
                    'balance' => $balance,
                    'amount' => $amount,
                    'response' => $response,
                ];

            }

        }

        return $data;

    }

    public function doEvents(\Illuminate\Http\Request $request){

        $get = $request->input();

        $data = [];

        $domains = Partners::where('is_active', 1)->get();

        $usernames = [];
        $partners = [];
        if(isset($get['pn'])){
            $usernames = Username::where('core_username.is_active', 1)
                ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
                ->leftJoin('member_members', 'core_username.member_id', '=', 'member_members.id')
                ->where('core_boards.partner_id', $get['pn'])
                ->where('core_boards.for_event', 1)
                ->select(
                    'core_username.*',
                    'member_members.name',
                    'member_members.phone'
                )
                ->get();

            $partners = Partners::findOrFail($get['pn']);
        }

        $data = [
            'get' => $get,
            'domains' => $domains,
            'usernames' => $usernames,
            'partner' => $partners
        ];

        return view('core/username::events', $data);

    }

    public function confirmGive(\Illuminate\Http\Request $request){

        $post = $request->input();

        if($post['type'] == "cancel"){
            Username::where('id', $post['id'])->update(['member_id' => null, 'member_at' => null, 'ev_status' => 0]);
            return response()->json(['status'=>true, 'msg'=>'ยกเลิกสิทธิ์ลูกค้าเรียบร้อย'], 200);
        }

        // Find from old system
        $member = Members::where('domain', $post['pn_old'])
            ->where('username', $post['phone'])
            ->first();

        if(!$member){
            return response()->json(['status'=>false, 'msg'=>'ไม่มีข้อมูลลูกค้าในระบบ Admin กรุณาตรวจสอบ!'], 200);
        }

        $member_id = $member->new_id;

        // Check customer have on new admin
        $new_member = [];
        if(empty($member->new_id)){
            // return response()->json(['status'=>false, 'msg'=>'ไม่มีข้อมูลลูกค้าในระบบ Admin ใหม่ กรุณาตรวจสอบ!'], 200);
            $mem = new MembersController();
            $new_member = $mem->saveMember($member->A_I);

            if($new_member['status'] == false){
                return response()->json(['status'=>false, 'msg'=>$new_member['message']], 200);
            }

            $member_id = $new_member['data']['id'];
        }

        // Check Exist
        $usernames = Username::where('core_username.is_active', 1)
            ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
            ->where('core_boards.partner_id', $post['pn'])
            ->where('core_boards.for_event', 1)
            ->where('core_username.member_id', $member_id)
            ->first();

        if($usernames){
            return response()->json(['status'=>false, 'msg'=>'ลูกค้าได้รับ Username ไปเรียบร้อยแล้ว '.$usernames->username], 200);
        }

        $usernames = Username::where('core_username.is_active', 1)
            ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
            ->where('core_boards.partner_id', $post['pn'])
            ->where('core_boards.for_event', 1)
            ->whereNULL('core_username.member_id')
            ->select(
                'core_username.*'
            )
            ->orderBy('id', 'asc')
            ->first();

        $pass = rand12(6);
        $password = Crypt::encryptString($pass);

        Username::where('id', $usernames->id)->update(['member_id' => $member_id, 'member_at' => date('Y-m-d H:i:d'), 'ev_password' => $password]);

        return response()->json(['status'=>true, 'msg'=>'แจกสิทธิ์ให้ลูกค้าเรียบร้อย คือ '.$usernames->username], 200);

    }

    public function genUsernameWithOld($member_id, $new_game_id)
    {

        $entity = \Modules\Member\Members\Entities\Members::where('old_id', $member_id)->first();

        if (empty($entity)) {
            $mem = new MembersController();
            $mem->addMember($member_id);
            $entity = \Modules\Member\Members\Entities\Members::where('old_id', $member_id)->first();
            // return $this->error('ไม่มีข้อมูลสมาชิก ที่ต้องการ Username');
        }

        if (empty($entity)) {
            return $this->error('ไม่มีข้อมูลสมาชิก ที่ต้องการ Username');
        }

        $this->entityIdentifier = $entity->id;

        $data = \Modules\Member\Members\Entities\Members::getUsernameCode($entity->agent_id, $entity->id);

//        return compact('entity', 'data');
//        print_r($data);
//        exit;

        if(empty($data)){
            return $this->error('Username ไม่พร้อมใช้งาน ไม่คงเหลือ');
        }

        $repository = $this->getRepository();

        if(!$data['exist']) { // Exist meaning already have boardNumber

            $arrUser = $data;

            unset($arrUser['exist']);
            unset($arrUser['username_id']);
            unset($arrUser['username']);

            $arrUser['member_id'] = $entity->id;
            $store = $repository->createEntity($arrUser, \App::make(MembersUsername::class));

        }
//        else{
//
//            return $this->error('Username มีอยู่แล้วในระบบ!');
//
//        }

        // Mask as member_is to username
        $boards = Boards::where('core_boards.is_active', 1)
            ->join('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->where('core_boards.partner_id', $data['partner_id'])
            ->where('core_boards.board_number', $data['board_number'])
//            ->where('core_games.is_active', 1)
//            ->where('core_games.old_id', $order['web_to']) // Filter only game identify from admin
//            ->where('core_games.id', $new_game_id) // Filter only game identify from admin
            ->orderBy('core_boards.id', 'asc')
            ->select('core_boards.*', 'core_games.name', 'core_games.old_id')
            ->get()->toArray();

        $date = date('Y-m-d H:i:s');

        $repository->setupModel(Username::class);

        $old_member = \App\Models\Old\Members::where('new_id', $entity->id)
            ->join('agent', 'member.agent', '=', 'agent.id')
            ->select('member.*', 'agent.reference')
            ->first()
            ->toArray();

//        print_r($data);
//        print_r($boards);
//        exit;


        $mask = 0;
        $arrUser = [];
        foreach ($boards as $board){

            $username = Username::where('board_id', $board['id'])
                ->whereNull('member_id')
                ->where('code', $data['username_code'])
                ->first();

//            print_r($username);
//            exit;

            if($username) {
                //$entity_username = $repository->find($username->id);

                $entity_username = Username::where('id', $username->id)
                    ->with(['usernameBoard' => function($query){
                        $query->select('id','partner_id','game_id');
                    }, 'usernameBoard.boardsGame' => function($query){
                        $query->select('id','old_id','name');
                    }, 'usernameBoard.boardsPartner' => function($query){
                        $query->select('id','old_id','name');
                    }])
                    ->first();

                $input = array(
                    'member_id' => $entity->id,
                    'member_at' => $date
                );
                $entity_username = $repository->updateEntity($input, $entity_username);

                if($new_game_id == $board['game_id']) {
                    $mask++;
                }

                $arr_username = $entity_username->toArray();

                // Create Username on Admin
                $arrData = array(
                    'company' => 1,
                    'new_id' => $arr_username['id'],
                    // 'locking' => $order,
                    'domain' => $old_member['domain'],
                    'agent' => $old_member['reference'],
                    'member_id' => $old_member['id'],
                    'member_username' => $old_member['username'],
                    'game_web' => $arr_username['username_board']['boards_game']['old_id'],
                    'web_name' => $arr_username['username_board']['boards_game']['name'],
                    'username' => $arr_username['username'],
                    'password' => Crypt::decryptString($arr_username['password']),
                    'created' => $date,
                    'created_by' => 1,
                    'created_from' => 'admin'
                );

                //$repository->createEntity($arrData, \App::make(\App\Models\Old\Username::class));

                $entityUsername = \App::make(\App\Models\Old\Username::class);
                foreach ($arrData as $field => $value) {
                    $entityUsername->setAttribute($field, $value);
                }

                $entityUsername->save();

                $arrUser[] = $arrData;

                // print_r($arrData);

            }

        }

//        if($mask == 0){
//            return $this->error('กรุณาสร้าง กระดานใหม่.');
//        }
        $res = compact('data', 'boards', 'arrUser');
        return $this->success('สร้าง Username เรียบร้อยแล้ว', $res);

    }

}
