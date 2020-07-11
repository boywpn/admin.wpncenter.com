<?php

namespace Modules\Core\Boards\Datatables;

use Illuminate\Support\Facades\DB;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Partners\Entities\Partners;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class PaymentsDatatable
 * @package Modules\Payments\Datatables
 */
class BoardsDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'core.boards.show';

    protected $editRoute = 'core.boards.edit';


    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('core/boards::boards.table.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'ref' => [
                'data' => 'ref',
                'title' => trans('core/boards::boards.table.ref'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'username_count' => [
                'data' => 'username_count',
                'title' => trans('core/boards::boards.table.username_count'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'members_count' => [
                'data' => 'members_count',
                'title' => trans('core/boards::boards.table.members_count'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'user_prefix' => [
                'data' => 'user_prefix',
                'title' => trans('core/boards::boards.table.user_prefix'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'board_number' => [
                'data' => 'board_number',
                'title' => trans('core/boards::boards.table.board_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'member_prefix' => [
                'data' => 'member_prefix',
                'title' => trans('core/boards::boards.table.member_prefix'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'member_limit' => [
                'data' => 'member_limit',
                'title' => trans('core/boards::boards.table.member_limit'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'agent' => [
                'name' => 'core_agents.id',
                'data' => 'agent',
                'title' => trans('core/boards::boards.table.agent_id'),
                'data_type' => 'text',
                'filter_type' => 'select',
                'select_type' => 'select2',
                'select_type_options' => [
                    'theme' => "bootstrap",
                    'width' => '100%'
                ],
                'filter_data' => Agents::getSelectOption()
            ],
            'partner' => [
                'name' => 'core_partners.id',
                'data' => 'partner',
                'title' => trans('core/boards::boards.table.partner_id'),
                'data_type' => 'text',
                'filter_type' => 'select',
                'select_type' => 'select2',
                'select_type_options' => [
                    'theme' => "bootstrap",
                    'width' => '100%'
                ],
                'filter_data' => Partners::getSelectOption()
            ],
            'game' => [
                'name' => 'core_games.id',
                'data' => 'game',
                'title' => trans('core/boards::boards.table.game_id'),
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
                'title' => trans('core/boards::boards.table.is_active'),
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
            'for_test' => [
                'title' => trans('core/boards::boards.table.for_test'),
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
            'for_agent' => [
                'title' => trans('core/boards::boards.table.for_agent'),
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
            'use_api' => [
                'title' => trans('core/boards::boards.table.use_api'),
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
            'report_api' => [
                'title' => trans('core/boards::boards.table.report_api'),
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
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE);

        $dataTable->editColumn('is_active', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, Boards::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('use_api', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->use_api, Boards::COLORS) . "'>".(($record->use_api) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('report_api', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->report_api, Boards::COLORS) . "'>".(($record->report_api) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('for_test', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->for_test, Boards::COLORS) . "'>".(($record->for_test) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('for_agent', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->for_agent, Boards::COLORS) . "'>".(($record->for_agent) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
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
    public function query(Boards $model)
    {
        $query = $model->newQuery()
            ->leftJoin('core_agents', 'core_boards.agent_id', '=', 'core_agents.id')
            ->leftJoin('core_partners', 'core_boards.partner_id', '=', 'core_partners.id')
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->where('core_games.is_active', 1)
            ->select(
                'core_boards.*',
                'core_agents.name as agent',
                'core_partners.name as partner',
                'core_games.name as game',
                DB::raw('(SELECT COUNT(*) FROM core_username WHERE core_username.board_id = core_boards.id) as username_count'),
                DB::raw('(SELECT COUNT(*) FROM core_username WHERE core_username.board_id = core_boards.id AND core_username.member_id IS NOT NULL) as members_count')
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
                'buttons' => DataTableHelper::buttons(),
                'regexp' => true,
                "order" => [[ 12, 'desc' ], [ 1, 'asc' ], [ 10, 'asc' ]]
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
