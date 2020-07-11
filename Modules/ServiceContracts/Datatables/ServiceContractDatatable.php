<?php

namespace Modules\ServiceContracts\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Modules\ServiceContracts\Entities\ServiceContract;
use Modules\ServiceContracts\Entities\ServiceContractPriority;
use Modules\ServiceContracts\Entities\ServiceContractStatus;
use Yajra\DataTables\EloquentDataTable;

class ServiceContractDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'servicecontracts.servicecontracts.show';

    protected $editRoute = 'servicecontracts.servicecontracts.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'service_contracts.name',
                'label' => trans('servicecontracts::servicecontracts.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'service_contracts.notes',
                'label' => trans('servicecontracts::servicecontracts.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'accounts.name',
                'label' => trans('servicecontracts::servicecontracts.form.account_name'),
                'type' => 'string',
            ],

            [
                'id' => 'service_contracts.created_at',
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
                'id' => 'service_contracts.updated_at',
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
                'id' => 'service_contracts.start_date',
                'label' => trans('servicecontracts::servicecontracts.table.start_date'),
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
                'id' => 'service_contracts.due_date',
                'label' => trans('servicecontracts::servicecontracts.table.due_date'),
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
                'id' => 'service_contracts.service_contract_priority_id',
                'label' => trans('servicecontracts::servicecontracts.form.service_contract_priority_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ServiceContractPriority::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'service_contracts.service_contract_status_id',
                'label' => trans('servicecontracts::servicecontracts.form.service_contract_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ServiceContractStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
        ];
    }

    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('servicecontracts::servicecontracts.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('servicecontracts::servicecontracts.form.account'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status' => [
                'name' => 'service_contracts_dict_status.name',
                'data' => 'status',
                'title' => trans('servicecontracts::servicecontracts.table.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'priority' => [
                'name' => 'service_contracts_dict_priority.name',
                'data' => 'priority',
                'title' => trans('servicecontracts::servicecontracts.table.priority'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'start_date' => [
                'data' => 'start_date',
                'title' => trans('servicecontracts::servicecontracts.form.start_date'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
            ],
            'due_date' => [
                'data' => 'due_date',
                'title' => trans('servicecontracts::servicecontracts.form.due_date'),
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
            DataTableHelper::queryOwner($query, $keyword,'service_contracts');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.updated_at', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('start_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.start_date', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('due_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('service_contracts.due_date', array($dates[0], $dates[1]));
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
    public function query(ServiceContract $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('service_contracts_dict_priority','service_contracts.service_contract_priority_id','=','service_contracts_dict_priority.id')
            ->leftJoin('service_contracts_dict_status','service_contracts.service_contract_status_id','=','service_contracts_dict_status.id')
            ->leftJoin('accounts','service_contracts.account_id','=','accounts.id')
            ->select(
                'service_contracts.*',
                'service_contracts_dict_priority.name as priority',
                'service_contracts_dict_status.name as status',
                'accounts.name as account_name'

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
