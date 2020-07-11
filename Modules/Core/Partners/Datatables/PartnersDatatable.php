<?php

namespace Modules\Core\Partners\Datatables;

use Modules\Core\Partners\Entities\Partners;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;

class PartnersDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'core.partners.show';

    protected $editRoute = 'core.partners.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'name',
                'label' => trans('core/partners::partners.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'code',
                'label' => trans('core/partners::partners.form.code'),
                'type' => 'string',
            ],
            [
                'id' => 'website',
                'label' => trans('core/partners::partners.form.website'),
                'type' => 'string',
            ],
            [
                'id' => 'phone',
                'label' => trans('core/partners::partners.form.phone'),
                'type' => 'string',
            ],
            [
                'id' => 'is_active',
                'label' => trans('core/partners::partners.form.is_active'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => [
                    '1' => trans('core::core.yes'),
                    '0' => trans('core::core.no'),
                ],
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'api_active',
                'label' => trans('core/partners::partners.form.api_active'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => [
                    '1' => trans('core::core.yes'),
                    '0' => trans('core::core.no'),
                ],
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'api_show_report',
                'label' => trans('core/partners::partners.form.api_show_report'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => [
                    '1' => trans('core::core.yes'),
                    '0' => trans('core::core.no'),
                ],
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
        ];
    }

    public static function availableColumns()
    {
        return
            [
                'name' => [
                    'data' => 'name',
                    'title' => trans('core/partners::partners.table.name'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'code' => [
                    'data' => 'code',
                    'title' => trans('core/partners::partners.table.code'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'owner_name' => [
                    'data' => 'owner_name',
                    'title' => trans('core/partners::partners.table.owner_id'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'website' => [
                    'data' => 'website',
                    'title' => trans('core/partners::partners.table.website'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'phone' => [
                    'data' => 'phone',
                    'title' => trans('core/partners::partners.table.phone'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'is_active' => [
                    'title' => trans('core/partners::partners.table.is_active'),
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
                    ]
                ],
                'api_active' => [
                    'title' => trans('core/partners::partners.table.api_active'),
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
                    ]
                ],
                'api_show_report' => [
                    'title' => trans('core/partners::partners.table.api_show_report'),
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
                    ]
                ]
            ];
    }

    protected function setFilterDefinition()
    {
        $this->filterDefinition = self::availableQueryFilters();
    }

    /**
     * DataTable definition
     *
     * @param Object $query Query object
     *
     * @return EloquentDataTable
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);


        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE);

        $dataTable->editColumn('is_active', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, Partners::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('api_active', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->api_active, Partners::COLORS) . "'>".(($record->api_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('api_show_report', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->api_show_report, Partners::COLORS) . "'>".(($record->api_show_report) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
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
     * @param Account $model
     *
     * @return $this
     */
    public function query(Partners $model)
    {

        $query = $model->newQuery()
            ->leftJoin('core_owners', 'core_owners.id', '=', 'core_partners.owner_id')
            ->select(
                'core_partners.*',
                'core_owners.name AS owner_name'
            );

        if (!empty($this->filterRules)) {
            $queryBuilderParser = new  QueryBuilderParser();
            $queryBuilder = $queryBuilderParser->parse($this->filterRules, $query);

            return $queryBuilder;
        }

        return $query;
    }

    /**
     * @return Builder
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
                'filterDefinitions' => $this->getFilterDefinition(),
                'filterRules' => $this->filterRules,
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
        if(!empty($this->advancedView)){
            return $this->advancedView;
        }

        $columns =  self::availableColumns();


        $result = [];

        if ($this->allowSelect) {
            $result =  $this->btnCheck_selection;
        }
        if ($this->allowUnlink) {
            $result =  $this->btnUnlink ;
        }
        if ($this->allowQuickEdit) {
            $result =  $result + $this->btnQuick_edit; ;
        }

        $result = $result + $columns;

        return $result;
    }
}
