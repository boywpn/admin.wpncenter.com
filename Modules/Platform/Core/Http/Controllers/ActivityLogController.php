<?php

namespace Modules\Platform\Core\Http\Controllers;

use Modules\Platform\Core\Datatable\ActivityLogDataTable;
use Modules\Platform\Core\Datatable\Scope\ActivityLogScope;
use Modules\Platform\Core\Repositories\ActivityLogRepository;
use Spatie\Activitylog\Models\Activity;

/**
 * Class ActivityLogController
 * @package Modules\Platform\Core\Http\Controllers
 */
class ActivityLogController extends AppBaseController
{
    private $activityLogRepo;

    public function __construct(ActivityLogRepository $repository)
    {
        $this->activityLogRepo = $repository;
        parent::__construct();
    }

    /**
     * @param $modelType
     * @param $entityId
     * @param ActivityLogDataTable $dataTable
     * @return mixed
     */
    public function activityLog($modelType, $entityId, ActivityLogDataTable $dataTable)
    {
        $dataTable->addScope(new ActivityLogScope($modelType, $entityId));


        return $dataTable->render('core::extension.activity_log.table');
    }

    /**
     * @param $entityId
     * @return \Illuminate\Contracts\View\View
     */
    public function detail($entityId)
    {
        $view =  \View::make('core::extension.activity_log.detail');

        $entity = $this->activityLogRepo->findWithoutFail($entityId);

        $view->with('entity', $entity);

        return $view;
    }
}
