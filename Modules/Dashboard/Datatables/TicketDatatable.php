<?php

namespace Modules\Dashboard\Datatables;

use Modules\Platform\Core\Helper\StringHelper;
use Modules\Tickets\Entities\Ticket;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Tickets\Entities\TicketPriority;
use Modules\Tickets\Entities\TicketSeverity;
use Modules\Tickets\Providers\TicketsServiceProvider;
use Yajra\DataTables\EloquentDataTable;

class TicketDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'tickets.tickets.show';

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

        $dataTable->editColumn('priority', function($record){
            return "<span class='badge ".StringHelper::badgeHelper($record->priority_id,TicketPriority::COLORS)."'>$record->priority</span>";
        });

        $dataTable->editColumn('severity', function($record){
            return "<span class='badge ".StringHelper::badgeHelper($record->severity_id,TicketSeverity::COLORS)."'>$record->severity</span>";
        });

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword);
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('tickets.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('due_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('tickets.due_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('tickets.updated_at', array($dates[0], $dates[1]));
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
    public function query(Ticket $model)
    {

        return $model->with('owner')
            ->leftJoin('tickets_dict_category', 'tickets.ticket_category_id', '=', 'tickets_dict_category.id')
            ->leftJoin('tickets_dict_priority', 'tickets.ticket_priority_id', '=', 'tickets_dict_priority.id')
            ->leftJoin('tickets_dict_severity', 'tickets.ticket_severity_id', '=', 'tickets_dict_severity.id')
            ->leftJoin('tickets_dict_status', 'tickets.ticket_status_id', '=', 'tickets_dict_status.id')
            ->newQuery()->select([
                'tickets.id',
                'tickets.name',
                'tickets.due_date',
                'tickets_dict_category.name as category',
                'tickets_dict_priority.id as priority_id',
                'tickets_dict_priority.name as priority',
                'tickets_dict_severity.id as severity_id',
                'tickets_dict_severity.name as severity',
                'tickets_dict_status.name as status',
                'tickets.created_at as created_at',
                'tickets.updated_at as updated_at',
                'tickets.owned_by_id',
                'tickets.owned_by_type'
            ]);

        return $model->with('owner')->newQuery()->select();
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
                        'filter_type' => 'bap_date_range_picker',
                    ],
                    [
                        'column_number' => 2,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 3,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 4,
                        'filter_type' => 'text'
                    ],
                    [
                        'column_number' => 5,
                        'filter_type' => 'bap_date_range_picker',
                    ],
                    [
                        'column_number' => 6,
                        'filter_type' => 'select',
                        'select_type' => 'select2',
                        'select_type_options' => [
                            'theme' => "bootstrap",
                            'width' => '100%'
                        ],
                        'data' => DataTableHelper::filterOwnerDropdown()
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
                'name' => [
                    'data' => 'name',
                    'title' => trans('core::core.table.name'),
                    'data_type' => 'text'
                ],
                'due_date' => [
                    'data' => 'due_date',
                    'title' => trans('core::core.table.due_date'),
                    'data_type' => 'date'
                ],
                'category' => [
                    'name' => 'tickets_dict_category.name',
                    'data' => 'category',
                    'title' => trans('tickets::tickets.table.category'),
                    'data_type' => 'text'
                ],
                'priority' => [
                    'name' => 'tickets_dict_priority.name',
                    'data' => 'priority',
                    'title' => trans('tickets::tickets.table.priority'),
                    'data_type' => 'text'
                ],
                'severity' => [
                    'name' => 'tickets_dict_severity.name',
                    'data' => 'severity',
                    'title' => trans('tickets::tickets.table.severity'),
                    'data_type' => 'text'
                ],

                'created_at' => [
                    'data' => 'created_at',
                    'title' => trans('core::core.table.created_at'),
                    'data_type' => 'datetime'
                ],
                'owner' => [
                    'data' => 'owner',
                    'title' => trans('core::core.table.assigned_to'),
                    'data_type' => 'assigned_to',
                    'orderable' => false
                ]
            ];
    }
}
