<?php

namespace Modules\Platform\Settings\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\User\Entities\DateFormat;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class DateformatDatatable
 * @package Modules\Platform\Settings\Datatables
 */
class DateformatDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'settings.dateformat.show';

    protected $editRoute = 'settings.dateformat.edit';

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
    public function query(DateFormat $model)
    {
        return $model->disableModelCaching()->newQuery()->select();
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
                'headerFilters' => true,
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
            $this->btnQuick_edit +
            [
                'name' => [
                    'data' => 'name',
                    'title' => trans('settings::dateformat.table.name'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'details' => [
                    'data' => 'details',
                    'title' => trans('settings::dateformat.table.php_format'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'js_details' => [
                    'data' => 'js_details',
                    'title' => trans('settings::dateformat.table.js_format'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'created_at' => [
                    'data' => 'created_at',
                    'title' => trans('settings::dateformat.table.created_at'),
                    'data_type' => 'datetime',
                    'filter_type' => 'text'
                ],
                'updated_at' => [
                    'data' => 'updated_at',
                    'title' => trans('settings::dateformat.table.updated_at'),
                    'data_type' => 'datetime',
                    'filter_type' => 'text'
                ]
            ];
    }
}
