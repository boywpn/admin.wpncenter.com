<?php

namespace Modules\Platform\User\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class UserActivityDatatable
 * @package Modules\Platform\User\Datatables
 */
class UserActivityDatatable extends PlatformDataTable
{

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        $this->applyLinks($dataTable, '');

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('created_at', array($dates[0], $dates[1]));
            }
        });

        return $dataTable;
    }

    /**
     * @param Activity $model
     * @return $this
     */
    public function query(Activity $model)
    {
        return $model->newQuery()->select();
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

                'stateSave' => false,
                'order' => [1, 'desc'],
                'columnFilters' => [
                    [
                        'column_number' => 0,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 1,
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
                    'title' => trans('user::users.table.description'),
                    'data_type' => 'text'
                ],
                'created_at' => [
                    'data' => 'created_at',
                    'title' => trans('user::users.table.created_at'),
                    'data_type' => 'datetime'
                ],
            ];
    }
}
