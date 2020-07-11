<?php

namespace Modules\Assets\Datatables;

use Modules\Assets\Entities\Asset;
use Modules\Assets\Entities\AssetCategory;
use Modules\Assets\Entities\AssetManufacturer;
use Modules\Assets\Entities\AssetStatus;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class AssetDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'assets.assets.show';

    protected $editRoute = 'assets.assets.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'assets.name',
                'label' => trans('assets::assets.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'assets.model_no',
                'label' => trans('assets::assets.form.model_no'),
                'type' => 'string',
            ],
            [
                'id' => 'assets.tag_number',
                'label' => trans('assets::assets.form.tag_number'),
                'type' => 'string',
            ],
            [
                'id' => 'assets.order_number',
                'label' => trans('assets::assets.form.order_number'),
                'type' => 'string',
            ],
            [
                'id' => 'assets.notes',
                'label' => trans('assets::assets.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'assets.purchase_cost',
                'label' => trans('assets::assets.form.purchase_cost'),
                'type' => 'double',
            ],
            [
                'id' => 'assets.purchase_date',
                'label' => trans('assets::assets.form.purchase_date'),
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
                'id' => 'assets.created_at',
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
                'id' => 'assets.updated_at',
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
            [
                'id' => 'assets.asset_status_id',
                'label' => trans('assets::assets.form.asset_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => AssetStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'assets.asset_category_id',
                'label' => trans('assets::assets.form.asset_category_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => AssetCategory::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'assets.asset_manufacturer_id',
                'label' => trans('assets::assets.form.asset_manufacturer_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => AssetManufacturer::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'accounts.name',
                'label' => trans('assets::assets.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.full_name',
                'label' => trans('assets::assets.form.contact_name'),
                'type' => 'string',
            ],

        ];
    }

    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('assets::assets.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'model_no' => [
                'data' => 'model_no',
                'title' => trans('assets::assets.form.model_no'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'tag_number' => [
                'data' => 'tag_number',
                'title' => trans('assets::assets.form.tag_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'order_number' => [
                'data' => 'order_number',
                'title' => trans('assets::assets.form.order_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'purchase_cost' => [
                'data' => 'purchase_cost',
                'title' => trans('assets::assets.form.purchase_cost'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'purchase_date' => [
                'data' => 'purchase_date',
                'title' => trans('assets::assets.form.purchase_date'),
                'data_type' => 'date',
                'filter_type' => 'bap_date_range_picker',
            ],
            'created_at' => [
                'data' => 'created_at',
                'title' => trans('assets::assets.form.created_at'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
            ],
            'updated_at' => [
                'data' => 'updated_at',
                'title' => trans('assets::assets.form.updated_at'),
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
            ],
            'category' => [
                'name' => 'assets_dict_category.name',
                'data' => 'category',
                'title' => trans('assets::assets.form.category'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'manufacturer' => [
                'name' => 'assets_dict_manufacturer.name',
                'data' => 'manufacturer',
                'title' => trans('assets::assets.form.manufacturer'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status' => [
                'name' => 'assets_dict_status.name',
                'data' => 'status',
                'title' => trans('assets::assets.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('assets::assets.form.account_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'contact_name' => [
                'name' => 'contacts.full_name',
                'data' => 'contact_name',
                'title' => trans('assets::assets.form.contact_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

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
     * @param Asset $model
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     * @throws \Modules\Platform\Core\QueryBuilderParser\QBParseException
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
