<?php

namespace Modules\Leads\Datatables\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Documents\Entities\Document;
use Modules\Leads\Datatables\LeadsDatatable;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Products\Datatables\ProductDatatable;
use Modules\Products\Entities\Product;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class LeadProductsDatatable
 * @package Modules\Leads\Datatables\Tabs
 */
class LeadProductsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'products.products.show';

    protected $unlinkRoute = 'leads.products.unlink';

    protected $editRoute = 'products.products.edit';

    public static function availableQueryFilters()
    {
        return ProductDatatable::availableQueryFilters();
    }

    public static function availableColumns()
    {
        return ProductDatatable::availableColumns();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'products_');


        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'products');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('products.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('products.updated_at', array($dates[0], $dates[1]));
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
    public function query(Product $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('products_dict_category', 'products.product_type_id', '=', 'products_dict_category.id')
            ->leftJoin('products_dict_type', 'products.product_category_id', '=', 'products_dict_type.id')
            ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
            ->select(
                'products.*',
                'vendors.name as vendor_name',
                'products_dict_category.name as category',
                'products_dict_type.name as type'
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
            ->setTableId('LeadProductDatatable' . $this->tableSuffix)
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

        $columns = ProductDatatable::availableColumns();

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
