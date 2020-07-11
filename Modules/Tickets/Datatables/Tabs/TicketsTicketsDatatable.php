<?php

namespace Modules\Tickets\Datatables\Tabs;

use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Tickets\Datatables\TicketDatatable;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Entities\TicketPriority;
use Modules\Tickets\Entities\TicketSeverity;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class ContactDealsDatatable
 * @package Modules\Contacts\Datatables\Tabs
 */
class TicketsTicketsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'tickets.tickets.show';

    protected $unlinkRoute = 'tickets.tickets.unlink';

    protected function setFilterDefinition()
    {
        $this->filterDefinition = TicketDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'tickets_');

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
            ->setTableId('TicketsTicketsDatatable' . $this->tableSuffix)
            ->columns($this->getColumns())
            ->minifiedAjax(route($this->route, ['entityId' => $this->entityId]))
            ->setTableAttribute('class', 'table table-hover')
            ->parameters([
                'dom' => 'lBfrtip',
                'responsive' => false,
                'stateSave' => true,
                'filterDefinitions' => $this->getFilterDefinition(),
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
        $unlink = [
            'unlink' => [
                'data' => 'unlink',
                'title' => '',
                'data_type' => 'unlink',
                'orderable' => false,
                'searchable' => false,

            ]
        ];

        $check_selection = [
            'check_select' => [
                'data' => 'check_select',
                'title' => '',
                'data_type' => 'check_select',
                'orderable' => false,
                'searchable' => false,
            ]
        ];

        $columns = TicketDatatable::availableColumns();


        if ($this->allowSelect) {
            return $check_selection + $columns;
        }
        if ($this->allowUnlink) {
            return $unlink + $columns;
        }

        return $columns;
    }
}
