<?php

namespace Modules\Vendors\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Vendors\Entities\Vendor;
use Modules\Vendors\Entities\VendorCategory;
use Yajra\DataTables\EloquentDataTable;

class VendorDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'vendors.vendors.show';

    protected $editRoute = 'vendors.vendors.edit';

    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('vendors::vendors.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'category' => [
                'name' => 'vendors_dict_category.name',
                'data' => 'category',
                'title' => trans('vendors::vendors.form.category'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'phone' => [
                'data' => 'phone',
                'title' => trans('vendors::vendors.form.phone'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'mobile' => [
                'data' => 'mobile',
                'title' => trans('vendors::vendors.form.mobile'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'email' => [
                'data' => 'email',
                'title' => trans('vendors::vendors.form.email'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'secondary_email' => [
                'data' => 'secondary_email',
                'title' => trans('vendors::vendors.form.secondary_email'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'fax' => [
                'data' => 'fax',
                'title' => trans('vendors::vendors.form.fax'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'skype_id' => [
                'data' => 'skype_id',
                'title' => trans('vendors::vendors.form.skype_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'street' => [
                'data' => 'street',
                'title' => trans('vendors::vendors.form.street'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'state' => [
                'data' => 'state',
                'title' => trans('vendors::vendors.form.state'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'country' => [
                'data' => 'country',
                'title' => trans('vendors::vendors.form.country'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'zip_code' => [
                'data' => 'zip_code',
                'title' => trans('vendors::vendors.form.zip_code'),
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

    public static function availableQueryFilters()
    {

        return [
            [
                'id' => 'vendors.name',
                'label' => trans('vendors::vendors.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.phone',
                'label' => trans('vendors::vendors.form.phone'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.mobile',
                'label' => trans('vendors::vendors.form.mobile'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.email',
                'label' => trans('vendors::vendors.form.email'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.secondary_email',
                'label' => trans('vendors::vendors.form.secondary_email'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.fax',
                'label' => trans('vendors::vendors.form.fax'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.skype_id',
                'label' => trans('vendors::vendors.form.skype_id'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.street',
                'label' => trans('vendors::vendors.form.street'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.state',
                'label' => trans('vendors::vendors.form.state'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.country',
                'label' => trans('vendors::vendors.form.country'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.zip_code',
                'label' => trans('vendors::vendors.form.zip_code'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.notes',
                'label' => trans('vendors::vendors.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.city',
                'label' => trans('vendors::vendors.form.city'),
                'type' => 'string',
            ],
            [
                'id' => 'vendors.created_at',
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
                'id' => 'vendors.updated_at',
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
                'id' => 'vendors.vendor_category_id',
                'label' => trans('vendors::vendors.form.vendor_category_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => VendorCategory::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
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
            DataTableHelper::queryOwner($query, $keyword);
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('vendors.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('vendors.updated_at', array($dates[0], $dates[1]));
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
    public function query(Vendor $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('vendors_dict_category','vendors.vendor_category_id','=','vendors_dict_category.id')
            ->select(
                'vendors.*',
                'vendors_dict_category.name as category'
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
