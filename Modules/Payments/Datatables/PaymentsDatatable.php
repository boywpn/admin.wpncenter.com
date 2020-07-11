<?php

namespace Modules\Payments\Datatables;

use Modules\Payments\Entities\Payment;
use Modules\Payments\Entities\PaymentCategory;
use Modules\Payments\Entities\PaymentPaymentMethod;
use Modules\Payments\Entities\PaymentStatus;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Platform\Settings\Entities\Currency;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class PaymentsDatatable
 * @package Modules\Payments\Datatables
 */
class PaymentsDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'payments.payments.show';

    protected $editRoute = 'payments.payments.edit';


    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('payments::payments.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'payment_date' => [
                'data' => 'payment_date',
                'title' => trans('payments::payments.form.payment_date'),
                'data_type' => 'date',
                'filter_type' => 'bap_date_range_picker'
            ],
            'amount' => [
                'data' => 'amount',
                'title' => trans('payments::payments.form.amount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'income' => [
                'data' => 'income',
                'title' => trans('payments::payments.form.income'),
                'data_type' => 'boolean',
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
            ],
            'currency' => [
                'name' => 'bap_currency.name',
                'data' => 'currency',
                'title' => trans('payments::payments.form.currency'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'category' => [
                'name' => 'payments_dict_category.name',
                'data' => 'category',
                'title' => trans('payments::payments.form.category'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'payment_method' => [
                'name' => 'payments_dict_payment_method.name',
                'data' => 'payment_method',
                'title' => trans('payments::payments.form.payment_method'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status' => [
                'name' => 'payments_dict_status.name',
                'data' => 'status',
                'title' => trans('payments::payments.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],


        ];
    }

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'payments.name',
                'label' => trans('payments::payments.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'payments.notes',
                'label' => trans('payments::payments.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'payments.amount',
                'label' => trans('payments::payments.form.amount'),
                'type' => 'double',
            ],

            [
                'id' => 'payments.payment_date',
                'label' => trans('payments::payments.form.payment_date'),
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
                'id' => 'payments.created_at',
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
                'id' => 'payments.updated_at',
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
                'id' => 'payments.payment_status_id',
                'label' => trans('payments::payments.form.payment_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => PaymentStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'payments.payment_category_id',
                'label' => trans('payments::payments.form.payment_category_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => PaymentCategory::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'payments.payment_payment_method_id',
                'label' => trans('payments::payments.form.payment_payment_method_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => PaymentPaymentMethod::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'payments.payment_currency_id',
                'label' => trans('payments::payments.form.payment_currency_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => Currency::pluck('code', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
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
            DataTableHelper::queryOwner($query, $keyword, 'payments');
        });

        $dataTable->filterColumn('payment_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);


            if ($dates != null) {
                $query->whereBetween('payments.payment_date', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('income', function ($query, $keyword) {

            if ($keyword == 'yes') {
                $query->where('payments.income', 1);
            } else {
                $query->where('payments.income', 0);
            }

        });
        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('payments.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('payments.updated_at', array($dates[0], $dates[1]));
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
    public function query(Payment $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('payments_dict_category', 'payments.payment_category_id', '=', 'payments_dict_category.id')
            ->leftJoin('payments_dict_payment_method', 'payments.payment_payment_method_id', '=', 'payments_dict_payment_method.id')
            ->leftJoin('payments_dict_status', 'payments.payment_status_id', '=', 'payments_dict_status.id')
            ->leftJoin('bap_currency', 'payments.payment_currency_id', '=', 'bap_currency.id')
            ->select(
                'payments.*',
                'payments_dict_category.name as category',
                'payments_dict_payment_method.name as payment_method',
                'payments_dict_status.name as status',
                'bap_currency.code as currency'
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
