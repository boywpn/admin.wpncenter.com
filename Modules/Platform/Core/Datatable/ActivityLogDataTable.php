<?php

namespace Modules\Platform\Core\Datatable;

use Modules\Platform\Core\Helper\DataTableHelper;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class ActivityLogDataTable
 * @package Modules\Platform\Core\Datatable
 */
class ActivityLogDataTable extends PlatformDataTable
{

    /**
     * @var
     */
    private $entityId;

    /**
     * @var
     */
    private $entityClass;

    /**
     * @param $entityClass
     * @param $entityId
     */
    public function setEntityData($entityClass, $entityId)
    {
        $this->entityClass = $entityClass;
        $this->entityId = $entityId;
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

        $this->applyLinks($dataTable, 'core.activity-log-details');

        $dataTable->addRowAttr('class', function ($record) {
            return 'open-in-modal';
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('created_at', array($dates[0], $dates[1]));
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
    public function query(Activity $model)
    {
        return $model->newQuery()->with('causer')->select();
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('ActivityLogDatatable')
            ->columns($this->getColumns())
            ->minifiedAjax(route('core.activity-log', ['model' => $this->entityClass,'entityId' =>$this->entityId]))
            ->setTableAttribute('class', 'table table-hover')
            ->parameters([
                'dom' => 'lBfrtip',

                'stateSave' => false,
                'order' => [2, 'desc'],
                'columnFilters' => [
                    [
                        'column_number' => 0,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 1,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 2,
                        'filter_type' => 'bap_date_range_picker',
                    ]
                ],
                'buttons' => DataTableHelper::buttons(),
                'regexp' => true

            ]);
    }

    /**
     * @return array
     */
    protected function getColumns()
    {
        return
            [
                'description' => [
                    'data' => 'description',
                    'title' => trans('core::core.table.description'),
                    'data_type' => 'text'
                ],
                'causer_id' => [
                    'data' => 'causer_id',
                    'title' => trans('core::core.table.updated_by'),
                    'data_type' => 'text',

                ],
                'created_at' => [
                    'data' => 'created_at',
                    'title' => trans('core::core.table.created_at'),
                    'data_type' => 'datetime',

                ],
            ];
    }
}
