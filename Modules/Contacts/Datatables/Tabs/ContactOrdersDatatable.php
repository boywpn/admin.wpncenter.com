<?php

namespace Modules\Contacts\Datatables\Tabs;

use Modules\Deals\Entities\Deal;
use Modules\Orders\Datatables\OrderDatatable;
use Modules\Orders\Entities\Order;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Tickets\Entities\Ticket;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class ContactOrdersDatatable
 * @package Modules\Contacts\Datatables\Tabs
 */
class ContactOrdersDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'orders.orders.show';

    protected $unlinkRoute = 'contacts.orders.unlink';

    protected $editRoute = 'orders.orders.edit';

    public static function availableColumns()
    {
        return OrderDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return OrderDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'orders_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword,'orders');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('orders.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('due_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('orders.due_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('order_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('orders.order_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('orders.updated_at', array($dates[0], $dates[1]));
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
    public function query(Order $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('orders_dict_carrier','orders.order_carrier_id','=','orders_dict_carrier.id')
            ->leftJoin('orders_dict_status','orders.order_status_id','=','orders_dict_status.id')
            ->leftJoin('accounts','orders.account_id','=','accounts.id')
            ->leftJoin('contacts','orders.contact_id','=','contacts.id')
            ->leftJoin('deals','orders.deal_id','=','deals.id')
            ->leftJoin('bap_currency','orders.currency_id','=','bap_currency.id')
            ->leftJoin('bap_tax','orders.tax_id','=','bap_tax.id')
            ->select(
                'orders.*',
                'orders_dict_carrier.name as carrier',
                'orders_dict_status.name as status',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',
                'deals.name as deal_name',
                'bap_currency.code as currency_name',
                'bap_tax.name as tax_name'
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
            ->setTableId('ContactOrdersDatatable' . $this->tableSuffix)
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

        $columns = OrderDatatable::availableColumns();

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
