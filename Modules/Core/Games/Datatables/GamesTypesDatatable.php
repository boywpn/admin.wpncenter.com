<?php

namespace Modules\Core\Games\Datatables;

use Modules\Core\Games\Entities\Games;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;

class GamesTypesDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'core.games-types.show';

    protected $editRoute = 'core.games-types.edit';

    public static function availableColumns()
    {
        return
            [
                'name' => [
                    'data' => 'name',
                    'title' => trans('core/games::games-types.table.name'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'code' => [
                    'data' => 'code',
                    'title' => trans('core/games::games-types.table.code'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'game' => [
                    'name' => 'core_games.id',
                    'data' => 'game_name',
                    'title' => trans('core/games::games-types.table.game_id'),
                    'data_type' => 'text',
                    'filter_type' => 'select',
                    'select_type' => 'select2',
                    'select_type_options' => [
                        'theme' => "bootstrap",
                        'width' => '100%'
                    ],
                    'filter_data' => Games::getSelectOption()
                ],
                'is_active' => [
                    'title' => trans('core/games::games-types.table.is_active'),
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
                'is_commission' => [
                    'title' => trans('core/games::games-types.table.is_commission'),
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
                'is_betlimit' => [
                    'title' => trans('core/games::games.table.is_betlimit'),
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
                'start_comm' => [
                    'data' => 'start_comm',
                    'title' => trans('core/games::games-types.table.start_comm'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'taking' => [
                    'data' => 'taking',
                    'title' => trans('core/games::games-types.table.taking'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'created_at' => [
                    'data' => 'created_at',
                    'title' => trans('core::core.table.created_at'),
                    'data_type' => 'datetime',
                    'filter_type' => 'bap_date_range_picker',
                ],
                'updated_at' => [
                    'data' => 'updated_at',
                    'title' => trans('core::core.table.updated_at'),
                    'data_type' => 'datetime',
                    'filter_type' => 'bap_date_range_picker',
                ],
            ];
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
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, GamesTypes::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('is_commission', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_commission, GamesTypes::COLORS) . "'>".(($record->is_commission) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('is_betlimit', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_betlimit, Games::COLORS) . "'>".(($record->is_betlimit) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('start_comm', function ($record) {
            return number_format($record->start_comm, 2);
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
    public function query(GamesTypes $model)
    {

        $query = $model->newQuery()
            ->leftJoin('core_games', 'core_games_types.game_id', '=', 'core_games.id')
            ->where('core_games.is_active', 1)
            ->select(
                'core_games_types.*',
                'core_games.name as game_name',
                'core_games.code as game_code'
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
