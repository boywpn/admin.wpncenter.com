<?php

namespace Modules\Orders\Datatables;

use Modules\Orders\Entities\Order;
use Modules\Orders\Entities\OrderCarrier;
use Modules\Orders\Entities\OrderStatus;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class OrderDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'orders.orders.show';

    protected $editRoute = 'orders.orders.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'orders.order_number',
                'label' => trans('orders::orders.form.order_number'),
                'type' => 'string',
            ],
            [
                'id' => 'orders.carrier_number',
                'label' => trans('orders::orders.form.carrier_number'),
                'type' => 'string',
            ],
            [
                'id' => 'deals.name',
                'label' => trans('orders::orders.form.deal_name'),
                'type' => 'string',
            ],
            [
                'id' => 'orders.customer_no',
                'label' => trans('orders::orders.form.customer_no'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.full_name',
                'label' => trans('orders::orders.form.contact_name'),
                'type' => 'string',
            ],
            [
                'id' => 'accounts.name',
                'label' => trans('orders::orders.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'orders.order_status_id',
                'label' => trans('orders::orders.form.order_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => OrderStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'orders.order_carrier_id',
                'label' => trans('orders::orders.form.order_carrier_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => OrderCarrier::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'bap_tax.name',
                'label' => trans('orders::orders.form.tax_name'),
                'type' => 'string',
            ],
            [
                'id' => 'bap_currency.code',
                'label' => trans('orders::orders.form.currency_name'),
                'type' => 'string',
            ],

            [
                'id' => 'orders.purchase_order',
                'label' => trans('orders::orders.form.purchase_order'),
                'type' => 'string',
            ],
            [
                'id' => 'orders.delivery_cost',
                'label' => trans('orders::orders.form.delivery_cost'),
                'type' => 'double',
            ],
            [
                'id' => 'orders.paid',
                'label' => trans('orders::orders.form.paid'),
                'type' => 'double',
            ],
            [
                'id' => 'orders.discount',
                'label' => trans('orders::orders.form.discount'),
                'type' => 'double',
            ],
            [
                'id' => 'orders.due_date',
                'label' => trans('orders::orders.form.due_date'),
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
                'id' => 'orders.created_at',
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
                'id' => 'orders.updated_at',
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
            'order_number' => [
                'data' => 'order_number',
                'title' => trans('orders::orders.form.order_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'carrier_number' => [
                'data' => 'carrier_number',
                'title' => trans('orders::orders.form.carrier_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'customer_no' => [
                'data' => 'customer_no',
                'title' => trans('orders::orders.form.customer_no'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'purchase_order' => [
                'data' => 'purchase_order',
                'title' => trans('orders::orders.form.purchase_order'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'due_date' => [
                'data' => 'due_date',
                'title' => trans('orders::orders.form.due_date'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
            ],
            'order_date' => [
                'data' => 'order_date',
                'title' => trans('orders::orders.form.order_date'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
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
            ],
            'discount' => [
                'data' => 'discount',
                'title' => trans('orders::orders.form.discount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'paid' => [
                'data' => 'paid',
                'title' => trans('orders::orders.form.paid'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'carrier' => [
                'name' => 'orders_dict_carrier.name',
                'data' => 'carrier',
                'title' => trans('orders::orders.form.carrier'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status' => [
                'name' => 'orders_dict_status.name',
                'data' => 'status',
                'title' => trans('orders::orders.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('orders::orders.form.account_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'contact_name' => [
                'name' => 'contacts.full_name',
                'data' => 'contact_name',
                'title' => trans('orders::orders.form.contact_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'deal_name' => [
                'name' => 'deals.name',
                'data' => 'deal_name',
                'title' => trans('orders::orders.form.deal_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'currency_name' => [
                'name' => 'bap_currency.code',
                'data' => 'currency_name',
                'title' => trans('orders::orders.form.currency_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'tax_name' => [
                'name' => 'bap_tax.name',
                'data' => 'tax_name',
                'title' => trans('orders::orders.form.tax_name'),
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
