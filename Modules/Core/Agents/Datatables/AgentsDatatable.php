<?php

namespace Modules\Core\Agents\Datatables;

use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Partners\Entities\Partners;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class PaymentsDatatable
 * @package Modules\Payments\Datatables
 */
class AgentsDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'core.agents.show';

    protected $editRoute = 'core.agents.edit';


    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('core/agents::agents.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'phone' => [
                'data' => 'phone',
                'title' => trans('core/agents::agents.form.phone'),
                'data_type' => 'text',
                'filter_type' => 'text'
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
            'status' => [
                'name' => 'core_agents_dict_status.name',
                'data' => 'status',
                'title' => trans('core/agents::agents.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'is_active' => [
                'title' => trans('core/agents::agents.table.is_active'),
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
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, Agents::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
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
    public function query(Agents $model)
    {
        $query = $model->newQuery()
            ->leftJoin('core_agents_dict_status', 'core_agents.status_id', '=', 'core_agents_dict_status.id')
            ->leftJoin('core_partners', 'core_agents.partner_id', '=', 'core_partners.id')
            ->select(
                'core_agents.*',
                'core_agents_dict_status.name as status',
                'core_partners.name as partner'
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
            $result =  $result + $this->btnQuick_edit;
        }

        $result = $result + $columns;

        return $result;
    }
}
