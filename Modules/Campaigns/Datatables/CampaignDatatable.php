<?php

namespace Modules\Campaigns\Datatables;

use Modules\Campaigns\Entities\Campaign;
use Modules\Campaigns\Entities\CampaignStatus;
use Modules\Campaigns\Entities\CampaignType;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class CampaignDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'campaigns.campaigns.show';

    protected $editRoute = 'campaigns.campaigns.edit';


    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'campaigns.name',
                'label' => trans('campaigns::campaigns.form.name'),
                'type' => 'string',
            ],
            [
                'id' => 'campaigns.product',
                'label' => trans('campaigns::campaigns.form.product'),
                'type' => 'string',
            ],
            [
                'id' => 'campaigns.notes',
                'label' => trans('campaigns::campaigns.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'campaigns.sponsor',
                'label' => trans('campaigns::campaigns.form.sponsor'),
                'type' => 'string',
            ],

            [
                'id' => 'campaigns.created_at',
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
                'id' => 'campaigns.updated_at',
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
                'id' => 'campaigns.expected_close_date',
                'label' => trans('campaigns::campaigns.form.expected_close_date'),
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
                'id' => 'campaigns.campaign_status_id',
                'label' => trans('campaigns::campaigns.form.campaign_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => CampaignStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'campaigns.campaign_type_id',
                'label' => trans('campaigns::campaigns.form.campaign_type_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => CampaignType::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'campaigns.target_audience',
                'label' => trans('campaigns::campaigns.form.target_audience'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.target_size',
                'label' => trans('campaigns::campaigns.form.target_size'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.budget_cost',
                'label' => trans('campaigns::campaigns.form.budget_cost'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.actual_budget',
                'label' => trans('campaigns::campaigns.form.actual_budget'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.expected_response',
                'label' => trans('campaigns::campaigns.form.expected_response'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.expected_revenue',
                'label' => trans('campaigns::campaigns.form.expected_revenue'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.expected_sales_count',
                'label' => trans('campaigns::campaigns.form.expected_sales_count'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.actual_sales_count',
                'label' => trans('campaigns::campaigns.form.actual_sales_count'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.expected_response_count',
                'label' => trans('campaigns::campaigns.form.expected_response_count'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.actual_response_count',
                'label' => trans('campaigns::campaigns.form.actual_response_count'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.expected_roi',
                'label' => trans('campaigns::campaigns.form.expected_roi'),
                'type' => 'double',
            ],
            [
                'id' => 'campaigns.actual_roi',
                'label' => trans('campaigns::campaigns.form.actual_roi'),
                'type' => 'double',
            ],
        ];
    }

    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('campaigns::campaigns.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status' => [
                'name' => 'campaigns_dict_status.name',
                'data' => 'status',
                'title' => trans('campaigns::campaigns.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'type' => [
                'name' => 'campaigns_dict_type.name',
                'data' => 'type',
                'title' => trans('campaigns::campaigns.form.type'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'product' => [
                'data' => 'product',
                'title' => trans('campaigns::campaigns.form.product'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'target_audience' => [
                'data' => 'target_audience',
                'title' => trans('campaigns::campaigns.form.target_audience'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'budget_cost' => [
                'data' => 'budget_cost',
                'title' => trans('campaigns::campaigns.form.budget_cost'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'actual_budget' => [
                'data' => 'actual_budget',
                'title' => trans('campaigns::campaigns.form.actual_budget'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'expected_response' => [
                'data' => 'expected_response',
                'title' => trans('campaigns::campaigns.form.expected_response'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'expected_revenue' => [
                'data' => 'expected_revenue',
                'title' => trans('campaigns::campaigns.form.expected_revenue'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'expected_sales_count' => [
                'data' => 'expected_sales_count',
                'title' => trans('campaigns::campaigns.form.expected_sales_count'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'actual_sales_count' => [
                'data' => 'actual_sales_count',
                'title' => trans('campaigns::campaigns.form.actual_sales_count'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'expected_response_count' => [
                'data' => 'expected_response_count',
                'title' => trans('campaigns::campaigns.form.expected_response_count'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'actual_response_count' => [
                'data' => 'actual_response_count',
                'title' => trans('campaigns::campaigns.form.actual_response_count'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'expected_roi' => [
                'data' => 'expected_roi',
                'title' => trans('campaigns::campaigns.form.expected_roi'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'actual_roi' => [
                'data' => 'actual_roi',
                'title' => trans('campaigns::campaigns.form.actual_roi'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'sponsor' => [
                'data' => 'sponsor',
                'title' => trans('campaigns::campaigns.form.sponsor'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'expected_close_date' => [
                'data' => 'expected_close_date',
                'title' => trans('campaigns::campaigns.form.expected_close_date'),
                'data_type' => 'date',
                'filter_type' => 'bap_date_range_picker'
            ],
            'target_size' => [
                'data' => 'target_size',
                'title' => trans('campaigns::campaigns.form.target_size'),
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
            DataTableHelper::queryOwner($query, $keyword,'campaigns');
        });

        $dataTable->editColumn('status', function($record){
            return "<span class='badge ".StringHelper::badgeHelper($record->status_id,CampaignStatus::COLORS)."'>$record->status</span>";
        });

        $dataTable->filterColumn('expected_close_date', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('campaigns.expected_close_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('campaigns.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('campaigns.updated_at', array($dates[0], $dates[1]));
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
    public function query(Campaign $model)
    {
        $query =  $model->with('owner')
            ->leftJoin('campaigns_dict_status', 'campaigns.campaign_status_id', '=', 'campaigns_dict_status.id')
            ->leftJoin('campaigns_dict_type', 'campaigns.campaign_type_id', '=', 'campaigns_dict_type.id')
            ->newQuery()->select([
                'campaigns.*',
                'campaigns_dict_status.id as status_id',
                'campaigns_dict_status.name as status',
                'campaigns_dict_type.name as type',
            ]);

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
