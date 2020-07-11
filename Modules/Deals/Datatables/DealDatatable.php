<?php

namespace Modules\Deals\Datatables;

use Modules\Deals\Entities\Deal;
use Modules\Deals\Entities\DealBusinessType;
use Modules\Deals\Entities\DealStage;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class DealDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'deals.deals.show';

    protected $editRoute = 'deals.deals.edit';


    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'deals.name',
                'label' => trans('deals::deals.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'accounts.name',
                'label' => trans('deals::deals.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'deals.notes',
                'label' => trans('deals::deals.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'deals.next_step',
                'label' => trans('deals::deals.form.next_step'),
                'type' => 'string',
            ],
            [
                'id' => 'deals.created_at',
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
                'id' => 'deals.updated_at',
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
                'id' => 'deals.closing_date',
                'label' => trans('deals::deals.form.closing_date'),
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
                'id' => 'deals.amount',
                'label' => trans('deals::deals.form.amount'),
                'type' => 'double',
            ],
            [
                'id' => 'deals.probability',
                'label' => trans('deals::deals.form.probability'),
                'type' => 'double',
            ],
            [
                'id' => 'deals.expected_revenue',
                'label' => trans('deals::deals.form.expected_revenue'),
                'type' => 'double',
            ],
            [
                'id' => 'deals.deal_stage_id',
                'label' => trans('deals::deals.form.deal_stage_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => DealStage::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'deals.deal_business_type_id',
                'label' => trans('deals::deals.form.deal_business_type_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => DealBusinessType::pluck('name', 'id'),
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
                'title' => trans('deals::deals.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'next_step' => [
                'data' => 'next_step',
                'title' => trans('deals::deals.form.next_step'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'probability' => [
                'data' => 'probability',
                'title' => trans('deals::deals.form.probability'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'expected_revenue' => [
                'data' => 'expected_revenue',
                'title' => trans('deals::deals.form.expected_revenue'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'amount' => [
                'data' => 'amount',
                'title' => trans('deals::deals.form.amount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'closing_date' => [
                'data' => 'closing_date',
                'title' => trans('deals::deals.form.closing_date'),
                'data_type' => 'date',
                'filter_type' => 'bap_date_range_picker'
            ],
            'business_type' => [
                'name' => 'deals_dict_business_type.name',
                'data' => 'business_type',
                'title' => trans('deals::deals.form.business_type'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'stage' => [
                'name' => 'deals_dict_stage.name',
                'data' => 'stage',
                'title' => trans('deals::deals.form.stage'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('deals::deals.form.account_name'),
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
            DataTableHelper::queryOwner($query, $keyword,'deals');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('deals.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('deals.updated_at', array($dates[0], $dates[1]));
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
    public function query(Deal $model)
    {

        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('deals_dict_business_type','deals.deal_business_type_id','=','deals_dict_business_type.id')
            ->leftJoin('deals_dict_stage','deals.deal_stage_id','=','deals_dict_stage.id')
            ->leftJoin('accounts','deals.account_id','=','accounts.id')

            ->select(
                'deals.*',
                'deals_dict_business_type.name as business_type',
                'deals_dict_stage.name as stage',
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
