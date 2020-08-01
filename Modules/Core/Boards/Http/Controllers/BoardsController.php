<?php

namespace Modules\Core\Boards\Http\Controllers;

use App\Http\Controllers\Games\Sa\MemberController AS SA;
use Illuminate\Support\Facades\Crypt;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Boards\Datatables\BoardsDatatable;
use Modules\Core\Boards\Datatables\Tabs\BoardsMembersDatatableTab;
use Modules\Core\Boards\Datatables\Tabs\BoardsUsersDatatableTab;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Boards\Http\Forms\BoardsForm;
use Modules\Core\Boards\Http\Requests\BoardsRequest;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class BoardsController extends ModuleCrudController
{
    protected $datatable = BoardsDatatable::class;
    protected $formClass = BoardsForm::class;
    protected $storeRequest = BoardsRequest::class;
    protected $updateRequest = BoardsRequest::class;
    protected $entityClass = Boards::class;

    protected $moduleName = 'CoreBoards';

    protected $permissions = [
        'browse' => 'core.boards.browse',
        'create' => 'core.boards.create',
        'update' => 'core.boards.update',
        'destroy' => 'core.boards.destroy'
    ];

    protected function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'href' => route('core.boards.create_members', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect pull-right'
            ],
            'label' => trans('core/boards::boards.create_members')
        );
    }

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-3'],
            'ref' => ['type' => 'text', 'col-class' => 'col-lg-3'],
            'user_prefix' => ['type' => 'text', 'col-class' => 'col-lg-3'],
            'board_number' => ['type' => 'text', 'col-class' => 'col-lg-3'],

            'partner_id' => ['type' => 'manyToOne', 'relation' => 'boardsPartner', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'agent_id' => ['type' => 'manyToOne', 'relation' => 'boardsAgent', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'game_id' => ['type' => 'manyToOne', 'relation' => 'boardsGame', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'member_limit' => ['type' => 'text', 'col-class' => 'col-lg-3'],
            'for_test' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],

            'is_active' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],
            'use_api' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],
            'report_api' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],
            'for_agent' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],

            'api_code' => ['type' => 'text', 'col-class' => 'col-lg-12'],

            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $relationTabs = [
        'username' => [
            'icon' => 'people',
            'permissions' => [
                'browse' => 'core.boards.users.browse',
                'update' => 'core.boards.users.update',
                'create' => 'core.boards.users.create'
            ],
            'datatable' => [
                'datatable' => BoardsUsersDatatableTab::class
            ],
            'route' => [
                'linked' => 'core.boards.users.linked',
                'create' => 'core.boards.users.create',
                'select' => 'core.boards.users.selection',
                'bind_selected' => 'core.boards.users.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'core/boards::users.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'board_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'core/boards::users.module'
            ],
        ],
        'member_username' => [
            'icon' => 'accessibility',
            'permissions' => [
                'browse' => 'core.username.browse',
                'update' => 'core.username.update',
                'create' => 'core.username.create'
            ],
            'datatable' => [
                'datatable' => BoardsMembersDatatableTab::class
            ],
            'route' => [
                'linked' => 'core.boards.members.linked',
                'create' => 'core.username.create',
                'select' => 'core.boards.members.selection',
                'bind_selected' => 'core.boards.members.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'core/username::username.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'board_id',
                ]
            ],
            'select' => [
                'allow' => false,
                'modal_title' => 'core/username::username.module'
            ],
            'custom_button' => [
                'btn_name' => 'core/username::username.create_new'
            ],
        ],
    ];

    protected $languageFile = 'core/boards::boards';

    protected $routes = [
        'index' => 'core.boards.index',
        'create' => 'core.boards.create',
        'show' => 'core.boards.show',
        'edit' => 'core.boards.edit',
        'store' => 'core.boards.store',
        'destroy' => 'core.boards.destroy',
        'update' => 'core.boards.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Multi Member Created OR Username
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createMembers($identifier)
    {

        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();
            return redirect(route($this->routes['index']));
        }

        $this->entityIdentifier = $entity->id;

        $member = Boards::find($entity->id)->membersBoard()
            ->orderBy('code', 'desc')
            ->get();

        $member = $member->toArray();

        if(count($member) == $entity->member_limit){
            flash(trans('core/boards::boards.max_limit'))->error();
            return redirect()->route($this->routes['show'], $entity->id);
        }

        if($member) {
            $code = (int)$member[0]['code'] + 1; // check last username
        }else{
            $code = 0;
        }

        $repository = $this->getRepository();

        for ($i = $code; $i <= $entity->member_limit; $i++){

            // $member_code = padZero($i, strlen($entity->member_limit-1));
            $member_code = padZero($i, 3);
            $username = $entity->member_prefix . $member_code;
            $password = Crypt::encryptString(rand12(8).rand(100,999).'@');

            $data = [
                'board_id' => $entity->id,
                'code' => $member_code,
                'username' => $username,
                'password' => $password,
                'token' => md5($entity->id.$member_code)
            ];

            $store = $repository->createEntity($data, \App::make(Username::class));
            $store->save();

        }

        flash(trans('core/boards::boards.create_username_success'))->success();
        return redirect()->route($this->routes['index']);

    }

    /**
     * Before entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function beforeStore($request)
    {

        $member_prefix = $request->user_prefix . $request->board_number;

        // Check Exist
        $count = Boards::where('partner_id', '=', $request->partner_id)
            ->where('game_id', '=', $request->game_id)
            ->where('member_prefix', '=', $member_prefix)->count();

        if($count > 0){
            $data = [
                'status' => false,
                'message' => trans('core/boards::boards.have_exist'),
                'route' => $this->routes['create']
            ];
            return $data;
        }

        $data = [
            'replace' => [
                'member_prefix' => $member_prefix
            ],
        ];

        return $data;

    }

    public function changePassUsername($entity){

        $game = Games::where('id', $entity)
            ->with(['boardsGame' => function($query){

            }, 'boardsGame.membersBoard' => function($query){

            }])
            ->first()
            ->toArray();

        foreach ($game['boards_game'] as $boards){

            foreach ($boards['members_board'] as $u) {

                $password = Crypt::encryptString(rand12(8) . rand(100, 999) . '@');

                Username::where('id', $u['id'])->update(['password' => $password]);

            }

        }

    }

    /**
     *
     * @param $request
     * @param $entity
     * @param $input
     */
    public function beforeUpdate($request, &$entity, &$input)
    {

        $member_prefix = $request->user_prefix . $request->board_number;

        if($entity->member_prefix != $member_prefix || $entity->partner_id != $input['partner_id'] || $entity->game_id != $input['game_id']) {

            // Check Exist
            $count = Boards::where('partner_id', '=', $request->partner_id)
                ->where('game_id', '=', $request->game_id)
                ->where('member_prefix', '=', $member_prefix)->count();

            if($count > 0){
                $data = [
                    'status' => false,
                    'message' => trans('core/boards::boards.have_exist'),
                    'route' => route($this->routes['edit'], $entity)
                ];
                return $data;
            }

            $input['member_prefix'] = $member_prefix;
        }

    }

    /**
     * Custom transfer credit by group
    */
    public function transferCredit($id){

        $board = Boards::findOrFail($id);

        $trans = new TransferApiController();

        if($board->game_id == 3) { // SA
            $key = json_decode($board->api_code, true);
            $api = new SA($key);

            $usernames = Username::where('board_id', $id)
                ->where('is_active', 1)
                ->whereNotNull('member_id')
//                ->offset(0)
//                ->limit(70)
                ->get();

            foreach ($usernames->toArray() as $username){

                print_r($username);

                $user = $username['username'];

                // Set 0
//                $setParam = [
//                    'method' => 'DebitAllBalanceDV',
//                    'Username' => $user,
//                    'OrderId' => 'OUT' . date('YmdHis') . $user,
//                ];
//                $api->setParam($setParam);
//                $response = $api->push();
//                $response = xmlDecode($response, true);

                // Bet Limit
//                $setParam = [
//                    'method' => 'SetBetLimit',
//                    'Username' => $user,
//                    'Set1' => 1,
//                    'Currency' => 'THB',
//                ];
//                $api->setParam($setParam);
//                $response = $api->push();
//                $response = xmlDecode($response, true);

//                $arrTrans = [
//                    'comm_id' => '',
//                    'action' => 'transfer',
//                    'orderid' => 'event_auto',
//                    'custid' => $user,
//                    'type' => 'transfer',
//                    'amount' => 100,
//                    'staffid' => 1,
//                    'from' => 'api_event_topup',
//                    'stateid' => '333333',
//                    'local_ip' => get_client_ip(),
//                    'auto' => true,
//                ];
//
//                $response = $trans->transfer($arrTrans);

//                print_r($response);

            }

        }

    }
}
