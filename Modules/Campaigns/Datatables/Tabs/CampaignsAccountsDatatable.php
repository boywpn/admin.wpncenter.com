<?php

namespace Modules\Campaigns\Datatables\Tabs;

use Modules\Accounts\Datatables\AccountDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class CampaignsAccountsDatatable
 * @package Modules\Campaigns\Datatables\Tabs
 */
class CampaignsAccountsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'accounts.accounts.show';

    protected $unlinkRoute = 'campaigns.accounts.unlink';

    protected $editRoute = 'accounts.accounts.edit';


    public static function availableColumns()
    {
        return AccountDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return AccountDatatable::availableQueryFilters();
    }

    protected function setFilterDefinition()
    {
        $this->filterDefinition = AccountDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'accounts_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword,'accounts');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('accounts.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('accounts.updated_at', array($dates[0], $dates[1]));
            }
        });

        return $dataTable;
    }

    /**
     * @param Account $model
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     * @throws \Modules\Platform\Core\QueryBuilderParser\QBParseException
     */
    public function query(Account $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('accounts_dict_industry','accounts.account_industry_id','=','accounts_dict_industry.id')
            ->leftJoin('accounts_dict_rating','accounts.account_rating_id','=','accounts_dict_rating.id')
            ->leftJoin('accounts_dict_type','accounts.account_type_id','=','accounts_dict_type.id')
            ->select(
                'accounts.*',
                'accounts_dict_industry.name as industry',
                'accounts_dict_rating.name as rating',
                'accounts_dict_type.name as type'
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
            ->setTableId('CampaigAaccountsDatatable' . $this->tableSuffix)
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

        $columns = AccountDatatable::availableColumns();

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
