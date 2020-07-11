<?php

namespace Modules\Accounts\Datatables\Tabs;

use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Quotes\Datatables\QuoteDatatable;
use Modules\Quotes\Entities\Quote;
use Modules\Tickets\Entities\Ticket;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class AccountQuotesDatatable
 * @package Modules\Accounts\Datatables\Tabs
 */
class AccountQuotesDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'quotes.quotes.show';

    protected $unlinkRoute = 'accounts.quotes.unlink';

    protected $editRoute = 'quotes.quotes.edit';

    public static function availableColumns()
    {
        return QuoteDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return QuoteDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'quotes_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'quotes');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('quotes.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('quotes.updated_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('valid_unitl', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('quotes.valid_unitl', array($dates[0], $dates[1]));
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
    public function query(Quote $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('quotes_dict_carrier', 'quotes.quote_carrier_id', '=', 'quotes_dict_carrier.id')
            ->leftJoin('quotes_dict_stage', 'quotes.quote_stage_id', '=', 'quotes_dict_stage.id')
            ->leftJoin('accounts', 'quotes.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'quotes.contact_id', '=', 'contacts.id')
            ->leftJoin('bap_currency', 'quotes.currency_id', '=', 'bap_currency.id')
            ->leftJoin('bap_tax', 'quotes.tax_id', '=', 'bap_tax.id')
            ->select(
                'quotes.*',
                'quotes_dict_carrier.name as carrier',
                'quotes_dict_stage.name as stage',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',
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
            ->setTableId('AccountQuotesDatatable' . $this->tableSuffix)
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

        $columns = QuoteDatatable::availableColumns();

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
