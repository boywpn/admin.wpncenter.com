<?php

namespace Modules\Core\Owners\Datatables;

use Modules\Core\Owners\Entities\Owners;
use Modules\Core\Partners\Entities\Partners;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;

class OwnersDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'core.owners.show';

    protected $editRoute = 'core.owners.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'name',
                'label' => trans('core/owners::owners.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'code',
                'label' => trans('core/owners::owners.form.code'),
                'type' => 'string',
            ],
            [
                'id' => 'phone',
                'label' => trans('core/owners::owners.form.phone'),
                'type' => 'string',
            ],
            [
                'id' => 'is_active',
                'label' => trans('core/owners::owners.form.is_active'),
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
                'id' => 'is_suspend',
                'label' => trans('core/owners::owners.form.is_suspend'),
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
                    'title' => trans('core/owners::owners.table.name'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'code' => [
                    'data' => 'code',
                    'title' => trans('core/owners::owners.table.code'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'phone' => [
                    'data' => 'phone',
                    'title' => trans('core/owners::owners.table.phone'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'is_active' => [
                    'title' => trans('core/owners::owners.table.is_active'),
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
                'is_suspend' => [
                    'title' => trans('core/owners::owners.table.is_suspend'),
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
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, Owners::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('is_suspend', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_suspend, Owners::COLORS) . "'>".(($record->is_suspend) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
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
    public function query(Owners $model)
    {

        $query = $model->newQuery()->select('*');

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
