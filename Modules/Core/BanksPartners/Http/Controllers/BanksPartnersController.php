<?php

namespace Modules\Core\BanksPartners\Http\Controllers;

use Modules\Core\Banks\Entities\Banks;
use Modules\Core\BanksPartners\Datatables\BanksPartnersDatatable;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\BanksPartners\Http\Forms\BanksPartnersForm;
use Modules\Core\BanksPartners\Http\Requests\BanksPartnersRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class BanksPartnersController extends ModuleCrudController
{
    protected $datatable = BanksPartnersDatatable::class;
    protected $formClass = BanksPartnersForm::class;
    protected $storeRequest = BanksPartnersRequest::class;
    protected $updateRequest = BanksPartnersRequest::class;
    protected $entityClass = BanksPartners::class;

    protected $moduleName = 'CoreBanksPartners';

    protected $permissions = [
        'browse' => 'core.bankspartners.browse',
        'create' => 'core.bankspartners.create',
        'update' => 'core.bankspartners.update',
        'destroy' => 'core.bankspartners.destroy'
    ];

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text'],
            'partner_id' => ['type' => 'manyToOne', 'relation' => 'banksPartner', 'column' => 'name'],
            'bank_id' => ['type' => 'manyToOne', 'relation' => 'banksBank', 'column' => 'account'],
            'member_status_id' => ['type' => 'manyToOne', 'relation' => 'banksMembersStatus', 'column' => 'name'],
            'is_active' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],
            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'core/bankspartners::bankspartners';

    protected $routes = [
        'index' => 'core.bankspartners.index',
        'create' => 'core.bankspartners.create',
        'show' => 'core.bankspartners.show',
        'edit' => 'core.bankspartners.edit',
        'store' => 'core.bankspartners.store',
        'destroy' => 'core.bankspartners.destroy',
        'update' => 'core.bankspartners.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
