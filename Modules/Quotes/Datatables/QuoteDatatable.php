<?php

namespace Modules\Quotes\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Entities\QuoteCarrier;
use Modules\Quotes\Entities\QuoteStage;
use Yajra\DataTables\EloquentDataTable;

class QuoteDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'quotes.quotes.show';

    protected $editRoute = 'quotes.quotes.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'quotes.name',
                'label' => trans('quotes::quotes.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'quotes.shipping',
                'label' => trans('quotes::quotes.form.shipping'),
                'type' => 'string',
            ],
            [
                'id' => 'quotes.notes',
                'label' => trans('quotes::quotes.form.notes'),
                'type' => 'string',
            ],

            [
                'id' => 'bap_tax.name',
                'label' => trans('quotes::quotes.form.tax_name'),
                'type' => 'string',
            ],
            [
                'id' => 'bap_currency.code',
                'label' => trans('quotes::quotes.form.currency_name'),
                'type' => 'string',
            ],
            [
                'id' => 'accounts.name',
                'label' => trans('quotes::quotes.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.full_name',
                'label' => trans('quotes::quotes.form.contact_name'),
                'type' => 'string',
            ],
            [
                'id' => 'quotes.quote_stage_id',
                'label' => trans('quotes::quotes.form.quote_stage_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => QuoteStage::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'quotes.quote_carrier_id',
                'label' => trans('quotes::quotes.form.quote_carrier_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => QuoteCarrier::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'quotes.discount',
                'label' => trans('quotes::quotes.form.discount'),
                'type' => 'double',
            ],
            [
                'id' => 'quotes.delivery_cost',
                'label' => trans('quotes::quotes.form.delivery_cost'),
                'type' => 'double',
            ],
            [
                'id' => 'quotes.amount',
                'label' => trans('quotes::quotes.form.amount'),
                'type' => 'double',
            ],
            [
                'id' => 'quotes.created_at',
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
                'id' => 'quotes.updated_at',
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
                'id' => 'quotes.valid_unitl',
                'label' => trans('quotes::quotes.form.valid_unitl'),
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
                'name' => 'quotes.name',
                'data' => 'name',
                'title' => trans('quotes::quotes.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'shipping' => [
                'name' => 'quotes.shipping',
                'data' => 'name',
                'title' => trans('quotes::quotes.form.shipping'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'valid_unitl' => [
                'data' => 'valid_unitl',
                'title' => trans('quotes::quotes.form.valid_unitl'),
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
                'title' => trans('quotes::quotes.form.discount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'amount' => [
                'data' => 'amount',
                'title' => trans('quotes::quotes.form.amount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],


            'delivery_cost' => [
                'data' => 'delivery_cost',
                'title' => trans('quotes::quotes.form.delivery_cost'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'stage' => [
                'name' => 'quotes_dict_stage.name',
                'data' => 'stage',
                'title' => trans('quotes::quotes.form.quote_stage_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'carrier' => [
                'name' => 'quotes_dict_carrier.name',
                'data' => 'carrier',
                'title' => trans('quotes::quotes.form.quote_carrier_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('quotes::quotes.form.account_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'contact_name' => [
                'name' => 'contacts.full_name',
                'data' => 'contact_name',
                'title' => trans('quotes::quotes.form.contact_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'currency_name' => [
                'name' => 'bap_currency.code',
                'data' => 'currency_name',
                'title' => trans('quotes::quotes.form.currency_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'tax_name' => [
                'name' => 'bap_tax.name',
                'data' => 'tax_name',
                'title' => trans('quotes::quotes.form.tax_name'),
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
            DataTableHelper::queryOwner($query, $keyword, 'quotes');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('quotes.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('quotes.updated_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('valid_unitl', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('quotes.valid_unitl', array($dates[0], $dates[1]));
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
    public function query(Quote $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('quotes_dict_carrier', 'quotes.quote_carrier_id', '=', 'quotes_dict_carrier.id')
            ->leftJoin('quotes_dict_stage', 'quotes.quote_stage_id', '=', 'quotes_dict_stage.id')
            ->leftJoin('accounts', 'quotes.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'quotes.contact_id', '=', 'contacts.id')
            ->leftJoin('bap_currency', 'quotes.currency_id', '=', 'bap_currency.id')
            ->leftJoin('bap_tax', 'quotes.tax_id', '=', 'bap_tax.id')
            ->select(
                'quotes.*',
                'quotes_dict_carrier.name as carrier',
                'quotes_dict_stage.name as stage',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',
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
