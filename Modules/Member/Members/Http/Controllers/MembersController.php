<?php

namespace Modules\Member\Members\Http\Controllers;

use App\Models\Banks;
use App\Models\Old\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Core\Username\Http\Controllers\UsernameController;
use Modules\Member\Members\Datatables\MembersDatatable;
use Modules\Member\Members\Datatables\Tabs\MembersBanksDatatableTab;
use Modules\Member\Members\Datatables\Tabs\MembersUsernameDatatableTab;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Member\Members\Entities\MembersCommissions;
use Modules\Member\Members\Entities\MembersCommissionsVar;
use Modules\Member\Members\Entities\MembersUsername;
use Modules\Member\Members\Http\Forms\MembersForm;
use Modules\Member\Members\Http\Requests\MembersRequest;
use Modules\Member\Members\Http\Requests\UpdatedMembersRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Traits\APITrait;
use App\Http\Controllers\Games\Dg\MemberController AS DG;
use App\Http\Controllers\Games\Og\MemberController AS OG;
use App\Http\Controllers\Games\Sa\MemberController AS SA;

class MembersController extends ModuleCrudController
{

    use APITrait;

    protected $datatable = MembersDatatable::class;
    protected $formClass = MembersForm::class;
    protected $storeRequest = MembersRequest::class;
    protected $updateRequest = UpdatedMembersRequest::class;
    protected $entityClass = Members::class;

    protected $moduleName = 'MemberMembers';

    protected $permissions = [
        'browse' => 'member.members.browse',
        'create' => 'member.members.create',
        'update' => 'member.members.update',
        'destroy' => 'member.members.destroy'
    ];

    protected $moduleSettingsLinks = [
        ['route' => 'member.members.status.index', 'label' => 'settings.status']
    ];

    protected function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'href' => route('member.members.set_bet_limit', $this->entity->id),
            'attr' => [
                'class' => 'btn btn-crud bg-blue waves-effect pull-right',
            ],
            'label' => trans('member/members::members.set_bet_limit')
        );

        $this->customShowButtons[] = array(
            'href' => route('member.members.gen_username', $this->entity->id),
            'attr' => [
                'class' => 'btn btn-crud bg-pink waves-effect pull-right'
            ],
            'label' => trans('member/members::members.gen_username')
        );
    }

    protected $settingsPermission = 'member.members.settings';

    protected $showFields = [
        'login' => [
            'username' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'password' => [
                'type' => 'none',
                'col-class' => 'col-lg-4',
                // 'hide_in_form_edit' => true
            ],
            'is_active' => ['type' => 'boolean', 'col-class' => 'col-lg-4'],
        ],

        'details' => [
            'name' => ['type' => 'text'],
            'phone' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'facebook' => ['type' => 'text'],
            'lineid' => ['type' => 'text'],
            'agent_id' => ['type' => 'manyToOne', 'relation' => 'membersAgent', 'column' => 'name'],
            'status_id' => ['type' => 'manyToOne', 'relation' => 'membersStatus', 'column' => 'name'],
            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $relationTabs = [
//        'commission' => [
//            'icon' => 'donut_small',
//            'permissions' => [
//                'browse' => 'member.members.commission'
//            ],
//            'custom' => [
//                'view' => 'member/members::commission',
//                'database' => Members::class
//            ]
//        ],
        'gamesconfig' => [
            'icon' => 'donut_small',
            'permissions' => [
                'browse' => 'member.members.gamesconfig'
            ],
            'custom' => [
                'view' => 'member/members::gamesConfig',
                'database' => Members::class
            ]
        ],
        'username' => [
            'icon' => 'accessibility',
            'permissions' => [
                'browse' => 'core.username.browse',
                'update' => 'core.username.update',
                'create' => 'core.username.create'
            ],
            'datatable' => [
                'datatable' => MembersUsernameDatatableTab::class
            ],
            'route' => [
                'linked' => 'member.members.username.linked',
                'create' => 'core.username.create',
                'select' => 'member.members.username.selection',
                'bind_selected' => 'member.members.username.link'
            ],
            'create' => [
                'allow' => false,
                'modal_title' => 'core/username::username.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'member_id',
                ]
            ],
            'select' => [
                'allow' => false,
                'modal_title' => 'core/username::username.module'
            ]
        ],
        'banks' => [
            'icon' => 'account_balance',
            'permissions' => [
                'browse' => 'member.members.banks.browse',
                'update' => 'member.members.banks.update',
                'create' => 'member.members.banks.create'
            ],
            'datatable' => [
                'datatable' => MembersBanksDatatableTab::class
            ],
            'route' => [
                'linked' => 'member.members.banks.linked',
                'create' => 'member.members.banks.create',
                'select' => 'member.members.banks.selection',
                'bind_selected' => 'member.members.banks.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'member/members::banks.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'member_id',
                ]
            ],
            'select' => [
                'allow' => false,
                'modal_title' => 'core/username::username.module'
            ]
        ],
    ];

    protected $languageFile = 'member/members::members';

    protected $routes = [
        'index' => 'member.members.index',
        'create' => 'member.members.create',
        'show' => 'member.members.show',
        'edit' => 'member.members.edit',
        'store' => 'member.members.store',
        'destroy' => 'member.members.destroy',
        'update' => 'member.members.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * For Generate by Manual
     */
    public function genAuto(){

        if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        for($i=6; $i<=50; $i++){

            $code = padZero($i, 3);
            $password = rand12(6);

            $username  = 'wpnbac'.$code;
            $name  = 'BACC Member'.$code;
            $phone = '0234567'.$code;

            $arrData = [
                'agent_id' => 129,
                'username' => $username,
                'password' => Hash::make($password),
                'password_key' => Crypt::encryptString(rand12(6)),
                'name' => $name,
                'phone' => $phone,
                'status_id' => 1,
                'notes' => 'สำหรับทำกิจกรรม Baccarat',
                'created_from' => 'admin'
            ];

//            $entity = $repository->createEntity($arrData, \App::make($this->entityClass));
//
//            $arrOldMember = [
//                'new_id' => $entity->id,
//                'id' => '15'.$phone,
//                'username' => $username,
//                'password' => $password,
//                'domain' => 5,
//                'status' => 1,
//                'level' => 1,
//                'agent' => 150,
//                'agent_name' => 'Baccarat',
//                'deposit_bank' => 1,
//                'deposit_bank_set' => 1,
//                'withdraw_bank' => 1,
//                'withdraw_ac' => '0000000000',
//                'withdraw_name' => $name,
//                'member.created' => date('Y-m-d H:i:s'),
//            ];
//
//            $member = $repository->createEntity($arrOldMember, \App::make(\App\Models\Old\Members::class));
//
//            print_r($arrData);

        }

    }

    /**
     * For Generate by Manual
     */
    public function genUsernameMember(){

        $members = Members::where('agent_id', 129)
            ->where('is_active', 1)
            ->where('id', '!=', 3195)
            ->get();

        $arr = [];
        foreach ($members as $member){

            // echo $member->id."\r\n";
            // $arr[] = $this->genUsernameMulti($member->id, ['board_id' => 52], $member->id);

        }

        print_r($arr);
//        $this->genUsername(58, 4);

    }

    /**
     * Multi Member Created
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function genUsernameMulti($identifier, $filter = null, $member_id = null)
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

        $data = Members::getUsernameCode($entity->agent_id, $entity->id, $filter);

        return $data;

        $memberOld = \App\Models\Old\Members::where('new_id', $member_id)->first();

//        return $memberOld;
//        print_r($data);
//        exit;

        if(empty($data)){
            flash(trans('member/members::members.no_member'))->error();
            return redirect(route($this->routes['show'], $entity->id));
        }

        $repository = $this->getRepository();

        $arrUsername = [];
        if(!$data['exist']) {

            $arrUser = $data;

            unset($arrUser['exist']);
            unset($arrUser['username_id']);
            unset($arrUser['username']);

            $arrUser['member_id'] = $entity->id;
            $store = $repository->createEntity($arrUser, \App::make(MembersUsername::class));
            $store->save();

            // Save to Username to Old System
            $arrUsername = [
                'new_id' => $data['username_id'],
                'company' => 1,
                'agent' => $memberOld->agent,
                'domain' => $memberOld->domain,
                'member_id' => $memberOld->id,
                'member_username' => $entity->username,
                'game_web' => 24,
                'web_name' => 'bacc',
                'username' => $data['username'],
                'game_user.created' => date('Y-m-d H:i:s')
            ];

            $repository->createEntity($arrUsername, \App::make(\App\Models\Old\Username::class));
        }

//        return $arrUsername;

        // Mask as member_is to username
        $boards = Boards::where('is_active', 1)
            ->where('partner_id', $data['partner_id'])
            ->where('board_number', $data['board_number'])
//            ->when(!empty($board_id), function($query) use ($board_id){
//                $query->where('id', $board_id);
//            })
            ->when(isset($filter['board_id']), function($query) use ($filter){
                $query->where('id', $filter['board_id']);
            })
            ->orderBy('id', 'asc')
            ->get()->toArray();

        $date = date('Y-m-d H:i:s');
        $repository->setupModel(Username::class);

//        return $boards;

        $mask = 0;
        foreach ($boards as $board){

            $username = Username::where('board_id', $board['id'])
                ->whereNull('member_id')
                ->where('code', $data['username_code'])
                ->first();

            if($username) {
                $entity_username = $repository->find($username->id);
                $input = array(
                    'member_id' => $entity->id,
                    'member_at' => $date
                );
                $entity_username = $repository->updateEntity($input, $entity_username);
                $mask++;
            }

        }

        return $arrUsername;

    }

    /**
     * Multi Member Created
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function genUsername($identifier, $filter = null, $member_id = null)
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

        $data = Members::getUsernameCode($entity->agent_id, $entity->id, $filter);

//        print_r($data);
//        exit;

        if(empty($data)){
            flash(trans('member/members::members.no_member'))->error();
            return redirect(route($this->routes['show'], $entity->id));
        }

        $repository = $this->getRepository();

        if(!$data['exist']) {

            $arrUser = $data;

            unset($arrUser['exist']);
            unset($arrUser['username_id']);
            unset($arrUser['username']);

            $arrUser['member_id'] = $entity->id;
            $store = $repository->createEntity($arrUser, \App::make(MembersUsername::class));
            $store->save();

        }

//        return $arrUsername;

        // Mask as member_is to username
        $boards = Boards::where('is_active', 1)
            ->where('partner_id', $data['partner_id'])
            ->where('board_number', $data['board_number'])
//            ->when(!empty($board_id), function($query) use ($board_id){
//                $query->where('id', $board_id);
//            })
            ->when(isset($filter['board_id']), function($query) use ($filter){
                $query->where('id', $filter['board_id']);
            })
            ->orderBy('id', 'asc')
            ->get()->toArray();

        $date = date('Y-m-d H:i:s');
        $repository->setupModel(Username::class);

        $mask = 0;
        foreach ($boards as $board){

            $username = Username::where('board_id', $board['id'])
                ->whereNull('member_id')
                ->where('code', $data['username_code'])
                ->first();

            if($username) {
                $entity_username = $repository->find($username->id);
                $input = array(
                    'member_id' => $entity->id,
                    'member_at' => $date
                );
                $entity_username = $repository->updateEntity($input, $entity_username);
                $mask++;
            }

        }

        flash(trans('member/members::members.mask_as').$mask." Username")->success();
        return redirect()->route($this->routes['show'], $entity->id);

    }

    /**
     * Set Bet Limit All Game
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setBetLimit($identifier)
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

        $username = Username::where('member_id', $entity->id)
            ->where('is_active', 1)
            ->with(['usernameBoard' => function($query){
                $query->select('id','partner_id','game_id','name','api_code');
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select('id','name','code');
            }])
            ->get();

        // print_r($username->toArray());

        foreach ($username->toArray() as $user){

            $username = $user['username'];
            $key = json_decode($user['username_board']['api_code'], true);
            $game = $user['username_board']['boards_game']['code'];

            if($game == "sa"){
                $api = new SA($key);
                $limit = [
                    'Set1' => 1,
                    'Set2' => 32,
                    'Set3' => 2048,
                    'Set4' => 1048576,
                    'Set5' => 4194304,
                ];
                $res = $api->setBetLimit($username, $limit);
            }

        }

        flash(trans('member/members::members.set_bet_limit_success'))->success();
        return redirect()->route($this->routes['show'], $entity->id);

    }

    /**
     * Before entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function beforeStore($request)
    {

        $password = Hash::make($request->get('password'));
        $data = [
            'replace' => [
                'password' => $password
            ]
        ];

        return $data;

    }

    /**
     *
     * @param $request
     * @param $entity
     * @param $input
     */
    public function beforeUpdate($request, &$entity, &$input)
    {
        if (isset($input['password'])) {
            if($entity->password != $input['password']) {
                $input['password'] = Hash::make($input['password']);
            }else{
                unset($input['password']);
            }
        }else{
            unset($input['password']);
        }
    }

    /**
     * API
    */
    public function addMember($id)
    {
        $member = $this->saveMember($id);

        if($member['status']){
            return $this->success($member['message'], $member['data']);
        }else{
            return $this->error($member['message']);
        }
    }
    public function saveMember($id)
    {

        $repository = $this->getRepository();

        $member = \App\Models\Old\Members::where('A_I', $id)
            ->with('membersDomain')
            ->first()->toArray();
        if(!$member){
            return $this->errorMsg('ไม่มีสมาชิกในระบบ');
        }

//        return $member;

        // Condition agent from old system
        if($member['agent'] == 1 && $member['domain'] == 5){
            $agent_id = '104'; // For Webpanun
        }
        elseif($member['agent'] == 1 && $member['domain'] == 2){
            $agent_id = '112'; // For Maxbet
        }
        elseif($member['agent'] == 1 && $member['domain'] == 3){
            $agent_id = '119'; // For Goal
        }
        elseif($member['agent'] == 1 && $member['domain'] == 1){
            $agent_id = '120'; // For Autobet
        }
        else{
            $agent = Agents::where('old_id', $member['agent'])->select('id')->first();
            if(!$agent){
                return $this->errorMsg('ไม่มีเอเจนท์ในระบบ');
            }
            $agent_id = $agent->id;
        }

//        $agent = Agents::where('old_id', $agent_id)->select('id')->first();
//        if(!$agent){
//            return $this->errorMsg('ไม่มีเอเจนท์ในระบบ');
//        }

        // $username_exist = Members::where('username', $member['username'])->count();
        $username_exist = Members::where('old_id', $id)->first();
        if($username_exist){
             return $this->errorMsg('Username นี้มีอยู่ในระบบแล้ว', $username_exist->toArray());
        }

        $bank = Banks::where('old_id', $member['withdraw_bank'])->select('id')->first();

        $status_id = 1;
        if($member['vip']){
            $status_id = 3;
            if($member['level'] == 5){
                $status_id = 4;
            }elseif($member['level'] == 6){
                $status_id = 5;
            }
        }elseif ($member['level'] == 0){
            $status_id = 1;
        }else{
            $status_id = 2;
        }

        /**
         * Create Member
        */
        $arrData = array(
            'old_id' => $member['A_I'],
            'agent_id' => $agent_id,
            'username' => $member['domain']."_".$member['username'],
            'password' => Hash::make($member['password']),
            'name' => $member['withdraw_name'],
            'phone' => $member['username'],
            'lineid' => $member['line_id'],
            'for_test' => $member['tester'],
            'status_id' => $status_id,
            'created_from' => 'admin',
            'company_id' => (isset(\Auth::user()->company_id)) ? \Auth::user()->company_id : $member['members_domain']['company']
        );
        $entity = $repository->createEntity($arrData, \App::make(Members::class)); // Save

        /**
         * Create Member's Bank
         */
        $arrBank = array(
            'member_id' => $entity->id,
            'bank_id' => $bank->id,
            'bank_account' => $member['withdraw_name'],
            'bank_number' => $member['withdraw_ac'],
            'is_main' => 1,
            'company_id' => (isset(\Auth::user()->company_id)) ? \Auth::user()->company_id : $member['members_domain']['company']
        );
        $repository->createEntity($arrBank, \App::make(MembersBanks::class)); // Save

        $data = array(
            'id' => $entity->id,
            'username' => $entity->username,
            'created_at' => $entity->created_at,
            'entity' => $entity->toArray()
        );

        // Update Old Member
        \App\Models\Old\Members::where('A_I', $member['A_I'])->update(['new_id' => $entity->id]);

        return $this->successMsg('เพิ่มสมาชิกเข้าระบบเรียบร้อย', $data);

    }

    public function genUsernameApi($identifier, $order_id = null, $auto_create = false, $checked = true)
    {

        if($checked) {
            if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
                return $this->error('ไม่สามารถเข้าถึงระบบนี้ได้ ' . $this->permissions['browse']);
            }
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        if (empty($entity)) {
            return $this->error('ไม่มีข้อมูลสมาชิก ที่ต้องการ Username');
        }

        $this->entityIdentifier = $entity->id;

        // Check GameID from Order
//        $order = Orders::where('id', $order_id)->first()->toArray();

        // Check New GameID
//        $game = Games::where('old_id', $order['web_to'])->first()->toArray();

        $data = Members::getUsernameCode($entity->agent_id, $entity->id);

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

        // Mask as member_is to username
        $boards = Boards::where('core_boards.is_active', 1)
            ->join('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->where('core_boards.partner_id', $data['partner_id'])
            ->where('core_boards.board_number', $data['board_number'])
//            ->whereNotNull('core_games.old_id')
//            ->where('core_games.old_id', $order['web_to']) // Filter only game identify from admin
//            ->where('core_games.id', $game['id']) // Filter only game identify from admin
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

//        print_r($order);
//        print_r($data);
//        print_r($boards);
//        exit;

        $mask = 0;
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

//                if($game['id'] == $board['game_id']) {
//                    $mask++;
//                }

                $arr_username = $entity_username->toArray();

                // Created Username Auto
                if($auto_create){
                    $u = new UsernameController();
                    $u->pushUsernameApi($arr_username['id']);
                }

                if(!empty($board['old_id'])) { // Insert only have old id

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
                        'created_from' => 'api'
                    );

                    //$repository->createEntity($arrData, \App::make(\App\Models\Old\Username::class));

                    $entityUsername = \App::make(\App\Models\Old\Username::class);
                    foreach ($arrData as $field => $value) {
                        $entityUsername->setAttribute($field, $value);
                    }

                    $entityUsername->save();

                }

                // print_r($arrData);

            }

        }

//        if($mask == 0){
//            return $this->error('กรุณาสร้าง กระดานใหม่.');
//        }

        return $this->success('สร้าง Username เรียบร้อยแล้ว', []);

    }

    public function saveCommission($member_id, Request $request){

        $data = $request->all();

        $repository = $this->getRepository();

        $input = [];
        $input['member_id'] = $member_id;

        $entity = $repository->createEntity($input, \App::make(MembersCommissions::class));

        $var = [];
        $var['comm_id'] = $entity->id;
        foreach ($data['type_id'] as $game => $type){

            $var['game_id'] = $game;
            $var['commissions'] = json_encode($type, JSON_FORCE_OBJECT);

            $repository->createEntity($var, \App::make(MembersCommissionsVar::class));

        }

        flash(trans('core::core.entity.updated'))->success();
        return redirect()->route($this->routes['show'], $member_id);

    }

    public function saveGamesConfig($member_id, Request $request){

        $data = $request->all();

        $repository = $this->getRepository();

        $input = [];
        $input['member_id'] = $member_id;

        $entity = $repository->createEntity($input, \App::make(MembersCommissions::class));

        $var = [];
        $var['comm_id'] = $entity->id;
        foreach ($data['type_id'] as $game => $type){

            $var['game_id'] = $game;
            $var['commissions'] = json_encode($type, JSON_FORCE_OBJECT);

            $repository->createEntity($var, \App::make(MembersCommissionsVar::class));

        }

        flash(trans('core::core.entity.updated'))->success();
        return redirect()->route($this->routes['show'], $member_id);

    }

}
