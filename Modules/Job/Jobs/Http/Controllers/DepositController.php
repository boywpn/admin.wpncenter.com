<?php

namespace Modules\Job\Jobs\Http\Controllers;

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

class DepositController extends ModuleCrudController
{
    protected $datatable = JobsDatatables::class;
    protected $formClass = JobsForm::class;
    protected $storeRequest = JobsRequest::class;
    protected $updateRequest = UpdateJobsRequest::class;
    protected $entityClass = Jobs::class;

    protected $moduleName = 'JobJobs';

    protected $permissions = [
        'deposit' => 'job.jobs.deposit'
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
        'deposit' => 'job.jobs.deposit',
    ];

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request)
    {

        $jobs_new = Jobs::getJobs(1, 1);
        $jobs_processing = Jobs::getJobs(1, 2);

        $data = [
            'jobs_new' => $jobs_new,
            'jobs_processing' => $jobs_processing,
        ];

        $view = view('job/jobs::deposit', $data);
        return $view;

    }

}
