<?php

namespace Modules\Contacts\Datatables\Tabs;

use Modules\Assets\Datatables\AssetDatatable;
use Modules\Assets\Entities\Asset;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class ContactAssetsDatatable
 * @package Modules\Contacts\Datatables\Tabs
 */
class ContactAssetsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'assets.assets.show';

    protected $unlinkRoute = 'contacts.assets.unlink';

    protected $editRoute = 'assets.assets.edit';

    public static function availableColumns()
    {
        return AssetDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return AssetDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'assets_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'assets');
        });

        $dataTable->filterColumn('purchase_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('assets.purchase_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('assets.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('assets.updated_at', array($dates[0], $dates[1]));
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
    public function query(Asset $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('assets_dict_category', 'assets.asset_category_id', '=', 'assets_dict_category.id')
            ->leftJoin('assets_dict_manufacturer', 'assets.asset_manufacturer_id', '=', 'assets_dict_manufacturer.id')
            ->leftJoin('assets_dict_status', 'assets.asset_status_id', '=', 'assets_dict_status.id')
            ->leftJoin('accounts', 'assets.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'assets.contact_id', '=', 'contacts.id')
            ->select(
                'assets.*',
                'assets_dict_category.name as category',
                'assets_dict_manufacturer.name as manufacturer',
                'assets_dict_status.name as status',
                'accounts.name as account_name',
                'contacts.full_name as contact_name'
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
            ->setTableId('ContactAssetsDatatable' . $this->tableSuffix)
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

        $columns = AssetDatatable::availableColumns();

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
