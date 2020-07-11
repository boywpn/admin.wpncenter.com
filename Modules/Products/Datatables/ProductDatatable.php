<?php

namespace Modules\Products\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Products\Entities\Product;
use Modules\Products\Entities\ProductCategory;
use Modules\Products\Entities\ProductType;
use Yajra\DataTables\EloquentDataTable;

class ProductDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'products.products.show';

    protected $editRoute = 'products.products.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'products.price',
                'label' => trans('products::products.form.price'),
                'type' => 'double',
            ],
            [
                'id' => 'products.name',
                'label' => trans('products::products.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'products.part_number',
                'label' => trans('products::products.form.part_number'),
                'type' => 'string',
            ],
            [
                'id' => 'products.vendor_part_number',
                'label' => trans('products::products.form.vendor_part_number'),
                'type' => 'string',
            ],
            [
                'id' => 'products.product_sheet',
                'label' => trans('products::products.form.product_sheet'),
                'type' => 'string',
            ],
            [
                'id' => 'products.website',
                'label' => trans('products::products.form.website'),
                'type' => 'string',
            ],
            [
                'id' => 'products.serial_no',
                'label' => trans('products::products.form.serial_no'),
                'type' => 'string',
            ],
            [
                'id' => 'products.notes',
                'label' => trans('products::products.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'products.image_path',
                'label' => trans('products::products.form.image_path'),
                'type' => 'string',
            ],
            [
                'id' => 'products.product_type_id',
                'label' => trans('products::products.form.product_type_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ProductType::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'products.product_category_id',
                'label' => trans('products::products.form.product_category_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ProductCategory::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'products.created_at',
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
                'id' => 'products.updated_at',
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
                'title' => trans('products::products.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'image_path' => [
                'data' => 'image_path',
                'title' => trans('products::products.table.image_path'),
                'data_type' => 'image'
            ],
            'price' => [
                'data' => 'price',
                'title' => trans('products::products.form.price'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'category' => [
                'name' => 'products_dict_category.name',
                'data' => 'category',
                'title' => trans('products::products.form.category'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'type' => [
                'name' => 'products_dict_type.name',
                'data' => 'type',
                'title' => trans('products::products.form.type'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'part_number' => [
                'data' => 'part_number',
                'title' => trans('products::products.form.part_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'vendor_part_number' => [
                'data' => 'vendor_part_number',
                'title' => trans('products::products.form.vendor_part_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'product_sheet' => [
                'data' => 'product_sheet',
                'title' => trans('products::products.form.product_sheet'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'website' => [
                'data' => 'website',
                'title' => trans('products::products.form.website'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'serial_no' => [
                'data' => 'serial_no',
                'title' => trans('products::products.form.serial_no'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'vendor_name' => [
                'name' => 'vendors.name',
                'data' => 'vendor_name',
                'title' => trans('products::products.form.vendor_name'),
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE);

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
            ->leftJoin('products_dict_category', 'products.product_category_id', '=', 'products_dict_category.id')
            ->leftJoin('products_dict_type', 'products.product_type_id', '=', 'products_dict_type.id')
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
            ->columns($this->getColumns())
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
        if(!empty($this->advancedView)){
            return $this->advancedView;
        }

        $columns =  self::availableColumns();


        $result = [];

        if ($this->allowSelect) {
            $result =  $this->btnCheck_selection;
        }
        if ($this->allowUnlink) {
            $result =  $this->btnUnlink ;
        }
        if ($this->allowQuickEdit) {
            $result =  $result + $this->btnQuick_edit; ;
        }

        $result = $result + $columns;

        return $result;
    }
}
