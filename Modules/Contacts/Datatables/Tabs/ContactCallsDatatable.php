<?php

namespace Modules\Contacts\Datatables\Tabs;

use Modules\Calls\Datatables\CallDatatable;
use Modules\Calls\Entities\Call;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Tickets\Entities\Ticket;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class ContactCallsDatatable
 * @package Modules\Contacts\Datatables\Tabs
 */
class ContactCallsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'calls.calls.show';

    protected $unlinkRoute = 'contacts.calls.unlink';

    protected $editRoute = 'calls.calls.edit';


    public static function availableColumns()
    {
        return CallDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return CallDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'calls_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'calls');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('calls.call_date', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('calls.call_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('calls.updated_at', array($dates[0], $dates[1]));
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
    public function query(Call $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('calls_dict_direction', 'calls.direction_id', '=', 'calls_dict_direction.id')
            ->leftJoin('accounts', 'calls.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'calls.contact_id', '=', 'contacts.id')
            ->leftJoin('leads', 'calls.lead_id', '=', 'leads.id')
            ->select(
                'calls.*',
                'calls_dict_direction.name as direction',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',
                'leads.full_name as lead_name'
            );

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
            ->setTableId('ContactsCallsDatatable' . $this->tableSuffix)
            ->columns($this->getColumns())
            ->minifiedAjax(route($this->route, ['entityId' => $this->entityId]))
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

        $columns = CallDatatable::availableColumns();

        $result = [];

        if ($this->allowSelect) {
            $result =  $this->btnCheck_selection;
        }
        if ($this->allowUnlink) {
            $result =  $this->btnUnlink ;
        }
        if ($this->allowUnlink) {
            $result =  $result + $this->btnQuick_edit; ;
        }

        $result = $result + $columns;

        return $result;
    }
}
