<?php

namespace Modules\Platform\Settings\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\User\Entities\TimeZone;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class TimeZoneDatatable
 * @package Modules\Platform\Settings\Datatables
 */
class TimeZoneDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'settings.timezone.show';

    protected $editRoute = 'settings.timezone.edit';

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
    public function query(TimeZone $model)
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
                        'filter_type' => 'select',
                        'select_type' => 'select2',
                        'select_type_options' => [
                            'theme' => "bootstrap",
                            'width' => '100%'
                        ],
                        'data' => [

                            [
                                'value' => 1,
                                'label' => trans('core::core.yes')
                            ],
                            [
                                'value' => 0,
                                'label' => trans('core::core.no')
                            ]
                        ]
                    ],
                    [
                        'column_number' => 3,
                        'filter_type' => 'bap_date_range_picker',

                    ],
                    [
                        'column_number' => 4,
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
            $this->btnQuick_edit +
            [
                'name' => [
                    'data' => 'name',
                    'title' => trans('settings::timezone.table.name'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'php_timezone' => [
                    'data' => 'php_timezone',
                    'title' => trans('settings::timezone.table.php_timezone'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'is_active' => [
                    'data' => 'is_active',
                    'title' => trans('settings::timezone.table.active'),
                    'data_type' => 'boolean',
                    'filter_type' => 'select',
                    'select_type' => 'select2',
                    'select_type_options' => [
                        'theme' => "bootstrap",
                        'width' => '100%'
                    ],
                    'filter_data' => [

                        [
                            'value' => 1,
                            'label' => trans('core::core.yes')
                        ],
                        [
                            'value' => 0,
                            'label' => trans('core::core.no')
                        ]
                    ]],
                'created_at' => [
                    'data' => 'created_at',
                    'title' => trans('settings::timezone.table.created_at'),
                    'data_type' => 'datetime',
                    'filter_type' => 'bap_date_range_picker'
                ],
                'updated_at' => [
                    'data' => 'updated_at',
                    'title' => trans('settings::timezone.table.updated_at'),
                    'data_type' => 'datetime',
                    'filter_type' => 'bap_date_range_picker'
                ]
            ];
    }
}
