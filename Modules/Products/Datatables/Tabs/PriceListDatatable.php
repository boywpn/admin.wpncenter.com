<?php

namespace Modules\Products\Datatables\Tabs;

use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Products\Entities\PriceList;
use Yajra\DataTables\EloquentDataTable;

class PriceListDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'products.price_list.show';

    protected $unlinkRoute = 'products.price_list.unlink';

    protected $deleteRoute = 'products.price_list.delete';

    protected $editRoute = 'products.price_list.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'price_list.price',
                'label' => trans('products::price_list.form.price'),
                'type' => 'double',
            ],
            [
                'id' => 'price_list.name',
                'label' => trans('products::price_list.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'products.name',
                'label' => trans('products::price_list.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'price_list.created_at',
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
                'id' => 'price_list.updated_at',
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
                'title' => trans('products::price_list.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'price' => [
                'data' => 'price',
                'title' => trans('products::price_list.form.price'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'product_name' => [
                'name' => 'products.name',
                'data' => 'product_name',
                'title' => trans('products::price_list.form.product_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
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

        $this->applyLinks($dataTable, '', 'price_list_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'price_list');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('price_list.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('price_list.updated_at', array($dates[0], $dates[1]));
            }
        });


        return $dataTable;
    }


    /**
     * @param PriceList $model
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     * @throws \Modules\Platform\Core\QueryBuilderParser\QBParseException
     */
    public function query(PriceList $model)
    {

        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('products', 'price_list.product_id', '=', 'products.id')
            ->select(
                'price_list.*',
                'products.name as product_name'
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
            ->setTableId('ProductProductPriceDatatable', $this->tableSuffix)
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
        $this->allowUnlink = false;
        $this->allowDelete = true;

        $result = $this->btnDelete + $this->btnQuick_edit;

        $result = $result + self::availableColumns();

        return $result;
    }
}
