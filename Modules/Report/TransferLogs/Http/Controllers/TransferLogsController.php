<?php

namespace Modules\Report\Transferlogs\Http\Controllers;

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
use Modules\Report\TransferLogs\Datatables\TransferLogsDatatables;
use Modules\Report\TransferLogs\Entities\TransferLogs;

class TransferLogsController extends ModuleCrudController
{
    protected $datatable = TransferLogsDatatables::class;
    protected $formClass = JobsForm::class;
    protected $storeRequest = JobsRequest::class;
    protected $updateRequest = UpdateJobsRequest::class;
    protected $entityClass = TransferLogs::class;

    protected $moduleName = 'JobJobs';

    protected $permissions = [
        'browse' => 'report.transferlogs.browse',
        'create' => 'report.transferlogs.create',
        'update' => 'report.transferlogs.update',
        'destroy' => 'report.transferlogs.destroy'
    ];

//    protected $moduleSettingsLinks = [
//        ['route' => 'report.transferlogsstatus.index', 'label' => 'settings.status']
//    ];
//
//    protected $settingsPermission = 'report.transferlogssettings';

    protected $showFields = [

    ];

    protected $languageFile = 'report/transferlogs::transfer';

    protected $routes = [
        'index' => 'report.transferlogs.index',
        'create' => 'report.transferlogs.create',
        'show' => 'report.transferlogs.show',
        'edit' => 'report.transferlogs.edit',
        'store' => 'report.transferlogs.store',
        'destroy' => 'report.transferlogs.destroy',
        'update' => 'report.transferlogs.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
