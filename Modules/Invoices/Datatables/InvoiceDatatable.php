<?php

namespace Modules\Invoices\Datatables;

use Modules\Invoices\Entities\Invoice;
use Modules\Invoices\Entities\InvoiceStatus;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class InvoiceDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'invoices.invoices.show';

    protected $editRoute = 'invoices.invoices.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'invoices.invoice_number',
                'label' => trans('invoices::invoices.form.invoice_number'),
                'type' => 'string',
            ],
            [
                'id' => 'orders.order_number',
                'label' => trans('invoices::invoices.form.order_number'),
                'type' => 'string',
            ],

            [
                'id' => 'invoices.customer_no',
                'label' => trans('invoices::invoices.form.customer_no'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.full_name',
                'label' => trans('invoices::invoices.form.contact_name'),
                'type' => 'string',
            ],
            [
                'id' => 'accounts.name',
                'label' => trans('invoices::invoices.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'invoices.invoice_status_id',
                'label' => trans('invoices::invoices.form.invoice_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => InvoiceStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],

            [
                'id' => 'bap_tax.name',
                'label' => trans('invoices::invoices.form.tax_name'),
                'type' => 'string',
            ],
            [
                'id' => 'bap_currency.code',
                'label' => trans('invoices::invoices.form.currency_name'),
                'type' => 'string',
            ],


            [
                'id' => 'invoices.account_number',
                'label' => trans('invoices::invoices.form.account_number'),
                'type' => 'string',
            ],

            [
                'id' => 'invoices.delivery_cost',
                'label' => trans('invoices::invoices.form.delivery_cost'),
                'type' => 'double',
            ],
            [
                'id' => 'invoices.paid',
                'label' => trans('invoices::invoices.form.paid'),
                'type' => 'double',
            ],
            [
                'id' => 'invoices.discount',
                'label' => trans('invoices::invoices.form.discount'),
                'type' => 'double',
            ],
            [
                'id' => 'invoices.invoice_date',
                'label' => trans('invoices::invoices.form.invoice_date'),
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
                'id' => 'invoices.due_date',
                'label' => trans('invoices::invoices.form.due_date'),
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
                'id' => 'invoices.created_at',
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
                'id' => 'invoices.updated_at',
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
            'invoice_number' => [
                'name' => 'invoices.invoice_number',
                'data' => 'invoice_number',
                'title' => trans('invoices::invoices.form.invoice_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'order_number' => [
                'name' => 'orders.order_number',
                'data' => 'order_number',
                'title' => trans('invoices::invoices.form.order_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'customer_no' => [
                'data' => 'customer_no',
                'title' => trans('invoices::invoices.form.customer_no'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'due_date' => [
                'data' => 'due_date',
                'title' => trans('invoices::invoices.form.due_date'),
                'data_type' => 'date',
                'filter_type' => 'bap_date_range_picker',
            ],
            'invoice_date' => [
                'data' => 'invoice_date',
                'title' => trans('invoices::invoices.form.invoice_date'),
                'data_type' => 'date',
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
                'title' => trans('invoices::invoices.form.discount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'paid' => [
                'data' => 'paid',
                'title' => trans('invoices::invoices.form.paid'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'status' => [
                'name' => 'invoices_dict_status.name',
                'data' => 'status',
                'title' => trans('invoices::invoices.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('invoices::invoices.form.account_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'contact_name' => [
                'name' => 'contacts.full_name',
                'data' => 'contact_name',
                'title' => trans('invoices::invoices.form.contact_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'currency_name' => [
                'name' => 'bap_currency.code',
                'data' => 'currency_name',
                'title' => trans('invoices::invoices.form.currency_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'tax_name' => [
                'name' => 'bap_tax.name',
                'data' => 'tax_name',
                'title' => trans('invoices::invoices.form.tax_name'),
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
            DataTableHelper::queryOwner($query, $keyword, 'invoices');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.updated_at', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('due_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.due_date', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('invoice_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('invoices.invoice_date', array($dates[0], $dates[1]));
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
    public function query(Invoice $model)
    {

        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('invoices_dict_status', 'invoices.invoice_status_id', '=', 'invoices_dict_status.id')
            ->leftJoin('accounts', 'invoices.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'invoices.contact_id', '=', 'contacts.id')
            ->leftJoin('orders', 'invoices.order_id', '=', 'orders.id')
            ->leftJoin('bap_currency', 'invoices.currency_id', '=', 'bap_currency.id')
            ->leftJoin('bap_tax', 'invoices.tax_id', '=', 'bap_tax.id')
            ->select(
                'invoices.*',
                'invoices_dict_status.name as status',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',
                'bap_currency.code as currency_name',
                'orders.order_number as order_number',
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
