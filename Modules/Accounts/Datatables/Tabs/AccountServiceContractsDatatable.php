<?php

namespace Modules\Accounts\Datatables\Tabs;

use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\ServiceContracts\Datatables\ServiceContractDatatable;
use Modules\ServiceContracts\Entities\ServiceContract;
use Modules\Tickets\Entities\Ticket;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class AccountServiceContractsDatatable
 * @package Modules\Accounts\Datatables\Tabs
 */
class AccountServiceContractsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'servicecontracts.servicecontracts.show';

    protected $unlinkRoute = 'accounts.servicecontracts.unlink';

    protected $editRoute = 'servicecontracts.servicecontracts.edit';

    public static function availableQueryFilters()
    {
        return ServiceContractDatatable::availableQueryFilters();
    }

    public static function availableColumns()
    {
        return ServiceContractDatatable::availableColumns();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'servicecontracts_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword,'service_contracts');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.updated_at', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('start_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.start_date', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('due_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.due_date', array($dates[0], $dates[1]));
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
    public function query(ServiceContract $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('service_contracts_dict_priority','service_contracts.service_contract_priority_id','=','service_contracts_dict_priority.id')
            ->leftJoin('service_contracts_dict_status','service_contracts.service_contract_status_id','=','service_contracts_dict_status.id')
            ->leftJoin('accounts','service_contracts.account_id','=','accounts.id')
            ->select(
                'service_contracts.*',
                'service_contracts_dict_priority.name as priority',
                'service_contracts_dict_status.name as status',
                'accounts.name as account_name'

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
            ->setTableId('AccountServiceContractsDatatable' . $this->tableSuffix)
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

        $columns = ServiceContractDatatable::availableColumns();

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
