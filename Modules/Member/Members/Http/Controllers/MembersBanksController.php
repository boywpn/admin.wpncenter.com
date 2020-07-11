<?php

namespace Modules\Member\Members\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Datatables\MembersBanksDatatable;
use Modules\Member\Members\Datatables\MembersDatatable;
use Modules\Member\Members\Datatables\Tabs\MembersUsernameDatatableTab;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Member\Members\Entities\MembersUsername;
use Modules\Member\Members\Http\Forms\MembersBanksForm;
use Modules\Member\Members\Http\Forms\MembersForm;
use Modules\Member\Members\Http\Requests\MembersBanksRequest;
use Modules\Member\Members\Http\Requests\MembersRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class MembersBanksController extends ModuleCrudController
{
    protected $datatable = MembersBanksDatatable::class;
    protected $formClass = MembersBanksForm::class;
    protected $storeRequest = MembersBanksRequest::class;
    protected $updateRequest = MembersBanksRequest::class;
    protected $entityClass = MembersBanks::class;

    protected $moduleName = 'MemberMembers';

    protected $permissions = [
        'browse' => 'member.members.banks.browse',
        'create' => 'member.members.banks.create',
        'update' => 'member.members.banks.update',
        'destroy' => 'member.members.banks.destroy'
    ];

    protected $showFields = [
        'information' => [
            'member_id' => ['type' => 'manyToOne', 'relation' => 'banksMember', 'column' => 'name'],
            'bank_id' => ['type' => 'manyToOne', 'relation' => 'banksBank', 'column' => 'name'],
            'bank_account' => ['type' => 'text'],
            'bank_number' => ['type' => 'text'],
            'is_main' => ['type' => 'boolean'],
            'is_active' => ['type' => 'boolean'],

            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'member/members::banks';

    protected $routes = [
        'index' => 'member.members.banks.index',
        'create' => 'member.members.banks.create',
        'show' => 'member.members.banks.show',
        'edit' => 'member.members.banks.edit',
        'store' => 'member.members.banks.store',
        'destroy' => 'member.members.banks.destroy',
        'update' => 'member.members.banks.update'
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

        if($request->is_main){

            MembersBanks::where('member_id', $request->member_id)
                ->update(['is_main' => 0]);

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

        if($input['is_main']){

            MembersBanks::where('member_id', $input['member_id'])
                ->update(['is_main' => 0]);

        }

    }
}
