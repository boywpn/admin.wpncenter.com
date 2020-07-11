<?php

namespace Modules\Tickets\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Entities\TicketCategory;
use Modules\Tickets\Entities\TicketPriority;
use Modules\Tickets\Entities\TicketSeverity;
use Modules\Tickets\Entities\TicketStatus;
use Yajra\DataTables\EloquentDataTable;

class TicketDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'tickets.tickets.show';

    protected $editRoute = 'tickets.tickets.edit';

    public static function availableQueryFilters()
    {

        return [
            [
                'id' => 'accounts.name',
                'label' => trans('tickets::tickets.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.full_name',
                'label' => trans('tickets::tickets.form.contact_name'),
                'type' => 'string',
            ],
            [
                'id' => 'tickets.name',
                'label' => trans('tickets::tickets.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'tickets.ticket_priority_id',
                'label' => trans('tickets::tickets.form.ticket_priority_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => TicketPriority::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'tickets.ticket_status_id',
                'label' => trans('tickets::tickets.form.ticket_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => TicketStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'tickets.ticket_severity_id',
                'label' => trans('tickets::tickets.form.ticket_severity_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => TicketSeverity::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'tickets.ticket_category_id',
                'label' => trans('tickets::tickets.form.ticket_category_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => TicketCategory::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'tickets.due_date',
                'label' => trans('tickets::tickets.form.due_date'),
                'type' => 'date',
                'input_event' => 'dp.change',
                'plugin' => 'datetimepicker',
                'plugin_config' => [
                    'locale' => app()->getLocale(),
                    'calendarWeeks' => true,
                    'showClear' => true,
                    'showTodayButton' => true,
                    'showClose' => true,
                    'format' => \Modules\Platform\Core\Helper\UserHelper::userJsDateFormat()
                ]
            ],
            [
                'id' => 'tickets.created_at',
                'label' => trans('core::core.table.created_at'),
                'type' => 'date',
                'input_event' => 'dp.change',
                'plugin' => 'datetimepicker',
                'plugin_config' => [
                    'locale' => app()->getLocale(),
                    'calendarWeeks' => true,
                    'showClear' => true,
                    'showTodayButton' => true,
                    'showClose' => true,
                    'format' => \Modules\Platform\Core\Helper\UserHelper::userJsDateFormat()
                ]
            ],
            [
                'id' => 'tickets.updated_at',
                'label' => trans('core::core.table.updated_at'),
                'type' => 'date',
                'input_event' => 'dp.change',
                'plugin' => 'datetimepicker',
                'plugin_config' => [
                    'locale' => app()->getLocale(),
                    'calendarWeeks' => true,
                    'showClear' => true,
                    'showTodayButton' => true,
                    'showClose' => true,
                    'format' => \Modules\Platform\Core\Helper\UserHelper::userJsDateFormat()
                ]
            ],
        ];
    }

    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('tickets::tickets.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text',
            ],

            'category' => [
                'name' => 'tickets_dict_category.name',
                'data' => 'category',
                'title' => trans('tickets::tickets.form.category'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'priority' => [
                'name' => 'tickets_dict_priority.name',
                'data' => 'priority',
                'title' => trans('tickets::tickets.form.priority'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'severity' => [
                'name' => 'tickets_dict_severity.name',
                'data' => 'severity',
                'title' => trans('tickets::tickets.form.severity'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status' => [
                'name' => 'tickets_dict_status.name',
                'data' => 'status',
                'title' => trans('tickets::tickets.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('tickets::tickets.form.account_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'contact_name' => [
                'name' => 'contacts.full_name',
                'data' => 'contact_name',
                'title' => trans('tickets::tickets.form.contact_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'due_date' => [
                'data' => 'due_date',
                'title' => trans('tickets::tickets.form.due_date'),
                'data_type' => 'text',
                'filter_type' => 'bap_date_range_picker'
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
            'owner' => [
                'data' => 'owner',
                'title' => trans('core::core.table.assigned_to'),
                'data_type' => 'assigned_to',
                'orderable' => false,

                'filter_type' => 'select',
                'select_type' => 'select2',
                'select_type_options' => [
                    'theme' => "bootstrap",
                    'width' => '100%'
                ],
                'filter_data' => DataTableHelper::filterOwnerDropdown()
            ]
        ];
    }

    protected function setFilterDefinition()
    {
        $this->filterDefinition = self::availableQueryFilters();
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

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword);
        });

        $dataTable->editColumn('priority', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->priority_id, TicketPriority::COLORS) . "'>$record->priority</span>";
        });
        $dataTable->editColumn('severity', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->severity_id, TicketSeverity::COLORS) . "'>$record->severity</span>";
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
        $query = $model->with('owner')
            ->leftJoin('tickets_dict_category', 'tickets.ticket_category_id', '=', 'tickets_dict_category.id')
            ->leftJoin('tickets_dict_priority', 'tickets.ticket_priority_id', '=', 'tickets_dict_priority.id')
            ->leftJoin('tickets_dict_severity', 'tickets.ticket_severity_id', '=', 'tickets_dict_severity.id')
            ->leftJoin('tickets_dict_status', 'tickets.ticket_status_id', '=', 'tickets_dict_status.id')
            ->leftJoin('accounts', 'tickets.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'tickets.contact_id', '=', 'contacts.id')
            ->newQuery()->select([

                'tickets.*',
                'tickets_dict_category.name as category',
                'tickets_dict_priority.id as priority_id',
                'tickets_dict_priority.name as priority',
                'tickets_dict_severity.id as severity_id',
                'tickets_dict_severity.name as severity',
                'tickets_dict_status.name as status',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',

            ]);

        if (!empty($this->filterRules)) {
            $queryBuilderParser = new  QueryBuilderParser();
            $queryBuilder = $queryBuilderParser->parse($this->filterRules, $query);

            return $queryBuilder;
        }

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
                'filterDefinitions' => $this->getFilterDefinition(),
                'filterRules' => $this->filterRules,
                'headerFilters' => true,
                'buttons' => DataTableHelper::buttons(),
                'regexp' => true
            ]);
    }

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
