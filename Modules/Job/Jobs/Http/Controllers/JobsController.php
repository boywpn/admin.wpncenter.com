<?php

namespace Modules\Job\Jobs\Http\Controllers;

use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\Promotions\Entities\Promotions;
use Modules\Core\Username\Entities\Username;
use Modules\Job\Jobs\Datatables\JobsDatatables;
use Modules\Job\Jobs\Entities\Jobs;
use Modules\Job\Jobs\Http\Forms\JobsForm;
use Modules\Job\Jobs\Http\Requests\JobsProRequest;
use Modules\Job\Jobs\Http\Requests\JobsRequest;
use Modules\Job\Jobs\Http\Requests\UpdateJobsRequest;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Illuminate\Http\Request;
use Modules\Platform\User\Entities\User;

class JobsController extends ModuleCrudController
{
    protected $datatable = JobsDatatables::class;
    protected $formClass = JobsForm::class;
    protected $storeRequest = JobsRequest::class;
    protected $updateRequest = UpdateJobsRequest::class;
    protected $entityClass = Jobs::class;

    protected $moduleName = 'JobJobs';

    protected $permissions = [
        'browse' => 'job.jobs.browse',
        'create' => 'job.jobs.create',
        'update' => 'job.jobs.update',
        'destroy' => 'job.jobs.destroy'
    ];

//    protected $moduleSettingsLinks = [
//        ['route' => 'job.jobs.status.index', 'label' => 'settings.status']
//    ];
//
//    protected $settingsPermission = 'job.jobs.settings';

    protected $showFields = [

    ];

    protected $languageFile = 'job/jobs::jobs';

    protected $routes = [
        'index' => 'job.jobs.index',
        'create' => 'job.jobs.create',
        'show' => 'job.jobs.show',
        'edit' => 'job.jobs.edit',
        'store' => 'job.jobs.store',
        'destroy' => 'job.jobs.destroy',
        'update' => 'job.jobs.update',
    ];

    public function __construct()
    {
        parent::__construct();
    }
    
    public function create(Request $request)
    {
        
        $view = view('job/jobs::create.search');

        return $view;

    }

    /**
     * Store entity
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {

        $request = \App::make($this->storeRequest ?? Request::class);

        $mode = $request->get('entityCreateMode', self::FORM_MODE_FULL);

        if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
            if ($mode == self::FORM_MODE_SIMPLE) {
                return response()->json([
                    'type' => 'error',
                    'message' => trans('core::core.entity.you_dont_have_access'),
                    'action' => 'show_message'
                ]);
            }
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $input = $this->getInputValues($request);

        $before = $this->beforeStoreInput($request, $input);

//        print_r($input);
//        exit;

        if(!empty($before)){
            flash($before['msg'])->error();
            return redirect(route($before['route']));
        }

        $entity = $repository->createEntity($input, \App::make($this->entityClass));

        $entity = $this->setupAssignedTo($entity, $request, true);
        $entity->save();

        $this->afterStore($request, $entity);

        flash(trans('core::core.entity.created'))->success();
        return redirect(route($this->routes['index']));
    }

    public function beforeStoreInput($request, &$input)
    {
        $pro_value = 0;

        // For Promotion
//        if($input['type_id'] == 1) {
//
//            if(empty($input['promotion_id'])) {
//                $data = [
//                    'msg' => trans('job/jobs::jobs.error.please_selected_promotion'),
//                    'route' => $this->routes['create']
//                ];
//                return $data;
//            }
//
//            $promotion = Promotions::find($input['promotion_id'])->toArray();
//            if($promotion['have_ref'] && empty($input['job_ref'])){
//                $data = [
//                    'msg' => trans('job/jobs::jobs.error.please_put_job_ref'),
//                    'route' => $this->routes['create']
//                ];
//                return $data;
//            }
//
//            if($promotion['have_ref']) {
//                $jobs = Jobs::where('code', $input['job_ref'])
//                    ->where('status_id', 3)
//                    ->where('type_id', 1)
//                    ->first();
//                if (empty($jobs)) {
//                    $data = [
//                        'msg' => trans('job/jobs::jobs.error.invalid_job_ref'),
//                        'route' => $this->routes['create']
//                    ];
//                    return $data;
//                }
//
//                $pro_value = Promotions::getPromotionValue($input['promotion_id'], $jobs->amount);
//
//                $input['job_ref_id'] = $jobs->id;
//            }else{
//                unset($input['job_ref']);
//
//                if(empty($input['promotion_amount'])){
//                    $data = [
//                        'msg' => trans('job/jobs::jobs.error.please_put_promotion_amount'),
//                        'route' => $this->routes['create']
//                    ];
//                    return $data;
//                }
//
//                $pro_value = $input['promotion_amount'];
//
//            }
//
//        }

        // For Deposit
        if(!empty($input['promotion_id']) && $input['type_id'] == 1) {
            $pro_value = Promotions::getPromotionValue($input['promotion_id'], $input['amount']);
        }

        $countOrder = Jobs::where('member_id', $input['member_id'])
            ->where('status_id', 3)
            ->count();

        $input['code'] = $input['code'] . '_' . $countOrder;
        $input['promotion_amount'] = $pro_value;
        $input['total_amount'] = $input['amount'] + $pro_value;

        // Set another value important for system
        $input['order_code'] = $input['code']; // For search transfer api only query check on order_code
        // $input['transfer_type'] = ($input['type_id'] != 3) ? $input['type_id'] : null; // For query check withdrawal or deposit

    }

    /**
     * After entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function afterStore($request, &$entity)
    {

        $date = date('Y-m-d');
        $picture = null;
        $deepPath = null;
        $picture = ($request->hasFile('topup_slip')) ? $request->file('topup_slip') : null;
        if($entity->type_id == 1){
            $deepPath = 'deposit_slip/' . $date . '/';
            $picturePath = config('job/jobs.job_picture_path') . $deepPath;
            //$picture = $request->file('topup_slip');
            $field = 'topup_slip';
        }

        $updateValue = [];

        if ($picture != null) {

            $image =  $entity->id . '_' . $entity->code . '.' . strtolower($picture->getClientOriginalExtension());

            $uploadSuccess = $picture->move($picturePath, $image);

            if ($uploadSuccess) {

                $updateValue[$field] = $deepPath . $image;

            }
        }

        $entity = $this->getRepository()->update($updateValue, $entity->id);

    }

    public function formCreate(Request $request)
    {

        $member_id = ($request->input('member_id')) ? $request->input('member_id') : "";

        if(empty($member_id)){
            flash(trans('job/jobs::jobs.error.please_put_member_id'))->error();
            return redirect()->back();
        }

        $member = Members::getMember($member_id);
        $bankspartner = BanksPartners::getBanksPartner($member['members_agent']['agents_partner']['id'], $member['status_id']);

//        print_r($member);
//        print_r($bankspartner);
//        exit;

        $view = view('job/jobs::create.create');

        $view->with('member', $member);
        $view->with('bankspartner', $bankspartner);
        $view->with('input', $request->input());

        return $view;

    }

    public function searchMember($type, Request $request){

        $term = $request->input('term');

        if($type == "username"){

            $usernames = Username::where('core_username.is_active', 1)
                ->whereNotNull('core_username.member_id')
                ->where('core_username.username', 'like', '%'.$term.'%')
                ->join('member_members', function ($join) {
                    $join->on('core_username.member_id', '=', 'member_members.id');
                })
                ->join('core_agents', function ($join) {
                    $join->on('member_members.agent_id', '=', 'core_agents.id');
                })
                ->join('core_partners', function ($join) {
                    $join->on('core_agents.partner_id', '=', 'core_partners.id');
                })
                ->join('core_boards', function ($join) {
                    $join->on('core_username.board_id', '=', 'core_boards.id');
                })
                ->join('core_games', function ($join) {
                    $join->on('core_boards.game_id', '=', 'core_games.id');
                })
                ->select(
                    'core_username.id',
                    'core_username.username',
                    'core_username.member_id',
                    'member_members.name as member_name',
                    'member_members.phone as member_phone',
                    'core_agents.name as agent_name',
                    'core_partners.name as partner_name',
                    'core_partners.website as partner_website',
                    'core_boards.name as board_name',
                    'core_games.name as game_name'
                )
                ->get()->toArray();

            $json = [];
            foreach ($usernames as $username){
                $json[] = array(
                    'id' => $username['id'],
                    'value' => $username['username'],
                    'label' => "[".$username['game_name']."] ".$username['username'],
                    'username' => $username['username'],
                    'member_id' => $username['member_id'],
                    'member_name' => $username['member_name'],
                    'member_phone' => 'N/A',
                    'game_name' => $username['game_name'],
                    'agent_name' => $username['agent_name'],
                    'partner_name' => $username['partner_name'],
                    'partner_website' => $username['partner_website'],
                );
            }

            $return = json_encode($json);

            return $return;

        }elseif($type == "name" || $type == "phone"){

            $members = Members::where('member_members.is_active', 1)
                ->when($type, function($query) use ($type, $term){
                    if($type == "name"){
                        $query->where('member_members.name', 'like', '%'.$term.'%');
                    }
                    if($type == "phone"){
                        $query->where('member_members.phone', $term);
                    }
                })
                ->join('core_agents', function ($join) {
                    $join->on('member_members.agent_id', '=', 'core_agents.id');
                })
                ->join('core_partners', function ($join) {
                    $join->on('core_agents.partner_id', '=', 'core_partners.id');
                })
                ->select(
                    'member_members.id',
                    'member_members.name as member_name',
                    'member_members.phone as member_phone',
                    'core_agents.name as agent_name',
                    'core_partners.name as partner_name',
                    'core_partners.website as partner_website'
                )
                ->get()->toArray();

            $json = [];
            foreach ($members as $member){
                $json[] = array(
                    'id' => $member['id'],
                    'value' => ($type == "phone") ? $member['member_phone'] : $member['member_name'],
                    'label' => ($type == "phone") ? $member['member_phone'] : $member['member_name'],
                    'username' => 'N/A',
                    'member_id' => $member['id'],
                    'member_name' => $member['member_name'],
                    'member_phone' => ($type == "phone") ? $member['member_phone'] : "N/A",
                    'game_name' => 'N/A',
                    'agent_name' => $member['agent_name'],
                    'partner_name' => $member['partner_name'],
                    'partner_website' => $member['partner_website'],
                );
            }

            $return = json_encode($json);

            return $return;

        }

    }

    public function getTableJobs($type){

        $jobs_new = Jobs::getJobs($type, 1);
        $jobs_processing = Jobs::getJobs($type, 2);

        $arrTable = [
            'new' => $this->genTableJobs($jobs_new),
            'processing' => $this->genTableJobs($jobs_processing),
        ];

        return json_encode($arrTable, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

    }
    public function genTableJobs($data){


        $table = "";
        foreach ($data as $job) {
            $date = explode(" ", $job['created_at']);

            $tr_bg = "";
            if($job['status_id'] == "1"){
                $tr_bg = "bg-blue-grey";
                if($job['username_id'] == 0){
                    $tr_bg = "bg-teal";
                }
            }elseif($job['status_id'] == "2"){
                $tr_bg = "bg-blue";
            }elseif($job['status_id'] == "3"){
                $tr_bg = "bg-green";
            }elseif($job['status_id'] == "4"){
                $tr_bg = "bg-red";
            }

            $table .= '<tr class="'.$tr_bg.'">';
            $table .= '<td title="'.$job['created_at'].'">'.$date[1].'</td>';
            $table .= '<td>'.$job['jobs_member']['members_agent']['agents_partner']['name'].'</td>';
            $table .= '<td class="bold">'.(($job['username_id'] != 0) ? $job['jobs_username']['username'] : 'To Wallet').'</td>';
            $table .= '<td class="bold">'.number_format($job['amount'],0).'</td>';
            $table .= '<td>';
            if($job['status_id'] == "1") {
                $table .= '<button type="button" class="btn btn-xs btn-info waves-effect"><i class="material-icons">lock</i></button> ';
            }
            $table .= '<button type="button" onclick="viewJob('.$job['id'].')" class="btn btn-xs bg-green waves-effect"><i class="material-icons">search</i></button>';
            $table .= '</td>';
            $table .= '</tr>';
        }

        return $table;

    }

    public function getJobByID($id){

        $job = Jobs::getJobByID($id);
        print_r($job);

    }

    public function renderJobByID($id){

        $job = Jobs::getJobByID($id);
        $data = [
            'job' => $job,
            'member' => $job['jobs_member'],
            'member_banks' => $job['jobs_member']['banks_member'],
            'username' => $job['jobs_username']
        ];
        $view = view('job/jobs::layouts.deposit', $data);
        return $view;

    }

    public function lockJobByID($id){

        $job = Jobs::lockJobByID($id);
        print_r($job);

    }

    public function createJob($data, $type){

        $input = [];

        if($type == 1){
            $type_id = 1;
            $transfer_type = 1; // Check type to statement -> wallet
        }
        if($type == 2){
            $type_id = 2;
            $transfer_type = 2; // Check type to wallet -> statement
        }

        // Check if from wallet then if have will change to wallet identify
        if(!empty($data['wallet_id']) && $type == 2){
            $type_id = 1;
            $transfer_type = 4; // Check type to wallet -> casino
            $input['wallet_id'] = $data['wallet_id'];
        }
        if(!empty($data['wallet_id']) && $type == 3){
            $type_id = 2;
            $transfer_type = 5; // Check type to casino -> wallet
            $input['wallet_id'] = $data['wallet_id'];
        }

        $input['order_id'] = null;
        $input['order_code'] = null;
        $input['ref_job_id'] = (!empty($data['ref_job_id'])) ? $data['ref_job_id'] : null;
        $input['code'] = time();
        $input['type_id'] = $type; // user original finance_type b/c need to use type of transfer for new system.
        $input['status_id'] = (!empty($data['status_id'])) ? $data['status_id'] : 2;
        $input['member_id'] = $data['member_id'];
        $input['wallet_id'] = (!empty($data['wallet_id'])) ? $data['wallet_id'] : null;
        $input['transfer_type'] = $transfer_type; // user for identify type of transaction.

        if($transfer_type == 4 || $transfer_type == 5) { // Only transfer wallet
            $username = Username::where('id', $data['username_id'])->where('member_id', $data['member_id'])->first();
            if (!$username) {
                return response()->json(['status' => false, 'Username ไม่มีในระบบ!']);
            }
        }

        if($transfer_type == 1){ // from statement
            $input['username_id'] = "0";
            $input['topup_from_bank'] = $data['topup_from_bank'];
            $input['topup_to_bank'] = $data['topup_to_bank'];
            $input['topup_pay_at'] = $data['topup_pay_at'];
        }
        // to statement
        elseif($transfer_type == 2){
            $input['username_id'] = "0";
            $input['withdraw_to_bank'] = $data['withdraw_to_bank'];
            $input['withdraw_at'] = null;
        }
        // to casino
        elseif($transfer_type == 4){
            $input['username_id'] = $data['username_id'];
            $input['username'] = $username->username;
            $input['topup_from_bank'] = null;
            $input['topup_to_bank'] = null;
            $input['topup_pay_at'] = null;
            $input['wallet_id'] = $data['wallet_id'];
        }
        // from casino
        elseif($transfer_type == 5){
            $input['username_id'] = $data['username_id'];
            $input['username'] = $username->username;
            $input['withdraw_to_bank'] = null;
            $input['withdraw_at'] = null;
            $input['wallet_id'] = $data['wallet_id'];
        }

        $input['amount'] = $data['amount'];
        $input['promotion_amount'] = 0;
        $input['total_amount'] = $data['amount'];

        $input['ip_address'] = get_client_ip();

        $input['company_id'] = $data['company_id'];

        $repository = $this->getRepository();

        $before = $this->beforeStoreInput($data, $input);

        if(!empty($before)){
//            $this->resJson['responseStatus']['code'] = 201;
//            $this->resJson['responseStatus']['message'] = "ERROR";
//            $this->resJson['responseStatus']['messageDetails'] = $before['msg'];
//            return $this->resJson;

            return ['status' => false, 'codeid' => 801, 'msg' => $before['msg']];
        }

        $entity = $repository->createEntity($input, \App::make($this->entityClass));

        // return compact('type', 'entity', 'transfer_type', 'input');

        // $this->afterStore($data, $entity);

        if($transfer_type == 4 || $transfer_type == 5) {
            $arrTrans = [
                'job_id' => $entity->id,
                'wallet_id' => $data['wallet_id'],
                'action' => 'transfer',
                'orderid' => 'new_system',
                'custid' => $username->username,
                'type' => 'transfer',
                'amount' => (string)round($data['amount'], 2),
                'staffid' => 1,
                'from' => 'new_system',
                'stateid' => null,
                'local_ip' => get_client_ip(),
                'auto' => true,
            ];

            // If set auto transfer is true
            if ($data['auto']) {
                $transfer = $this->setTransfer($arrTrans);
                if ($transfer['responseStatus']['code'] != 200) {
                    if ($transfer['responseStatus']['code'] == 203) {
                        return ['status' => false, 'codeid' => 601, 'msg' => $transfer['responseStatus']['messageDetails'], 'job_id' => $entity->id, 'data' => $transfer];
                    } else {
                        return ['status' => false, 'codeid' => 602, 'msg' => $transfer['responseStatus']['messageDetails'], 'job_id' => $entity->id, 'data' => $transfer];
                    }
                }

                return ['status' => true, 'codeid' => 0, 'job_id' => $entity->id, 'data' => $transfer];
            }
        }

        return ['status' => true, 'codeid' => 0, 'job_id' => $entity->id];

    }

    public function setTransfer($request)
    {

        $api = new TransferApiController();
        return $api->transfer($request);

    }

}
