<?php

namespace Modules\Accounts\Datatables\Tabs;

use Modules\Deals\Entities\Deal;
use Modules\Invoices\Datatables\InvoiceDatatable;
use Modules\Invoices\Entities\Invoice;
use Modules\Orders\Entities\Order;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Quotes\Entities\Quote;
use Modules\Tickets\Entities\Ticket;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class AccountInvoicesDatatable
 * @package Modules\Accounts\Datatables\Tabs
 */
class AccountInvoicesDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'invoices.invoices.show';

    protected $unlinkRoute = 'accounts.invoices.unlink';

    protected $editRoute   = 'invoices.invoices.edit';

    public static function availableColumns()
    {
        return InvoiceDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return InvoiceDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'invoices_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'invoices');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.updated_at', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('due_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.due_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('invoice_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.invoice_date', array($dates[0], $dates[1]));
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
    public function query(Invoice $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('invoices_dict_status', 'invoices.invoice_status_id', '=', 'invoices_dict_status.id')
            ->leftJoin('accounts', 'invoices.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'invoices.contact_id', '=', 'contacts.id')
            ->leftJoin('orders', 'invoices.order_id', '=', 'orders.id')
            ->leftJoin('bap_currency', 'invoices.currency_id', '=', 'bap_currency.id')
            ->leftJoin('bap_tax', 'invoices.tax_id', '=', 'bap_tax.id')
            ->select(
                'invoices.*',
                'invoices_dict_status.name as status',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',
                'bap_currency.code as currency_name',
                'orders.order_number as order_number',
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
            ->setTableId('AccountInvoicesDatatable' . $this->tableSuffix)
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

        $columns = InvoiceDatatable::availableColumns();

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
