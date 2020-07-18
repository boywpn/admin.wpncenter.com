<?php

namespace Modules\Job\Jobs\Datatables;

use App\Models\TransfersLog;
use App\Models\Trnf\TransferLogs;
use Modules\Core\Agents\Entities\Agents;
use Modules\Job\Jobs\Entities\Jobs;
use Modules\Job\Jobs\Entities\JobsStatus;
use Modules\Job\Jobs\Entities\JobsType;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class PaymentsDatatable
 * @package Modules\Payments\Datatables
 */
class JobsDatatables extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'job.jobs.show';

    protected $editRoute = 'job.jobs.edit';


    public static function availableColumns()
    {
        return [
            'created_at' => [
                'data' => 'created_at',
                'title' => trans('core::core.table.created_at'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
            ],
            'code' => [
                'data' => 'code',
                'title' => trans('job/jobs::jobs.table.code'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'order_code' => [
                'data' => 'order_code',
                'title' => trans('job/jobs::jobs.table.order_code'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'ref' => [
                'data' => 'ref',
                'title' => trans('job/jobs::jobs.table.ref'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'member_id' => [
                'name' => 'member_members.name',
                'data' => 'member_name',
                'title' => trans('job/jobs::jobs.table.member_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'username_id' => [
                'name' => 'core_username.username',
                'data' => 'username',
                'title' => trans('job/jobs::jobs.table.username_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'total_amount' => [
                'data' => 'total_amount',
                'title' => trans('job/jobs::jobs.table.total_amount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'credit_bf' => [
                //'name' => 'transfers_log.credit_bf',
                //'data' => 'credit_bf',
                'title' => trans('job/jobs::jobs.table.credit_bf'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'credit_af' => [
                //'name' => 'transfers_log.credit_af',
                //'data' => 'credit_af',
                'title' => trans('job/jobs::jobs.table.credit_af'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'type_id' => [
                'data' => 'type_id',
                'title' => trans('job/jobs::jobs.table.type_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status_id' => [
                'data' => 'status_id',
                'title' => trans('job/jobs::jobs.table.status_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

//            'amount' => [
//                'data' => 'amount',
//                'title' => trans('job/jobs::jobs.table.amount'),
//                'data_type' => 'text',
//                'filter_type' => 'text'
//            ],
//            'promotion_amount' => [
//                'data' => 'promotion_amount',
//                'title' => trans('job/jobs::jobs.table.promotion_amount'),
//                'data_type' => 'text',
//                'filter_type' => 'text'
//            ],
//            'updated_at' => [
//                'data' => 'updated_at',
//                'title' => trans('core::core.table.updated_at'),
//                'data_type' => 'datetime',
//                'filter_type' => 'bap_date_range_picker',
//            ],

        ];
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE);

        $dataTable->editColumn('status_id', function ($record) {
            return StringHelper::badgeFullHelper($record->status_id, JobsStatus::badgeTable());
        });

        $dataTable->editColumn('type_id', function ($record) {
            return StringHelper::badgeFullHelper($record->type_id, JobsType::badgeTable());
        });

        $dataTable->editColumn('credit_bf', function ($record) {
            $transfer = TransfersLog::where('job_id', $record->id)->first();
            return ($transfer) ? $transfer->credit_bf : null;
        });
        $dataTable->editColumn('credit_af', function ($record) {
            $transfer = TransfersLog::where('job_id', $record->id)->first();
            return ($transfer) ? $transfer->credit_af : null;
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('updated_at', array($dates[0], $dates[1]));
            }
        });

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Jobs $model)
    {
        $query = $model->newQuery()
            ->leftJoin('job_jobs_dict_status', 'job_jobs.status_id', '=', 'job_jobs_dict_status.id')
            ->leftJoin('job_jobs_dict_type', 'job_jobs.type_id', '=', 'job_jobs_dict_type.id')
            ->leftJoin('member_members', 'job_jobs.member_id', '=', 'member_members.id')
            ->leftJoin('core_username', 'job_jobs.username_id', '=', 'core_username.id')
            // ->leftJoin('transfers_log', 'job_jobs.id', '=', 'transfers_log.job_id')
            ->select(
                'job_jobs.*',
                'job_jobs_dict_status.name as status',
                'job_jobs_dict_type.name as type',
                'member_members.name as member_name',
                'core_username.username as username'
                //'transfers_log.credit_bf',
                //'transfers_log.credit_af'
            );

        return $query;

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->setTableAttribute('class', 'table table-hover')
            ->parameters([
                'dom' => 'lBfrtip',
                'responsive' => false,
                'stateSave' => true,
                'filterRules' => $this->filterRules,
                'headerFilters' => true,
                'order' => [[ 0, 'desc' ]],
                'buttons' => DataTableHelper::buttons(),
                'regexp' => true

            ]);
    }

    /**
     * @return array
     */
    protected function getColumns()
    {
        if(!empty($this->advancedView)){
            return $this->advancedView;
        }

        $columns =  self::availableColumns();


        $result = [];

//        if ($this->allowSelect) {
//            $result =  $this->btnCheck_selection;
//        }
//
//        if ($this->allowUnlink) {
//            $result =  $this->btnUnlink ;
//        }
//
//        if ($this->allowQuickEdit) {
//            $result =  $result + $this->btnQuick_edit; ;
//        }

        $result = $result + $columns;

        return $result;
    }
}
