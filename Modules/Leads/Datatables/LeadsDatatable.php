<?php

namespace Modules\Leads\Datatables;

use Modules\Leads\Entities\Lead;
use Modules\Leads\Entities\LeadIndustry;
use Modules\Leads\Entities\LeadRating;
use Modules\Leads\Entities\LeadSource;
use Modules\Leads\Entities\LeadStatus;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class LeadsDatatable
 * @package Modules\Leads\Datatables
 */
class LeadsDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'leads.leads.show';

    protected $editRoute = 'leads.leads.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'leads.full_name',
                'label' => trans('leads::leads.form.full_name'),
                'type' => 'string',
            ],
            [
                'id' => 'leads.email',
                'label' => trans('leads::leads.form.email'),
                'type' => 'string',
            ],
            [
                'id' => 'leads.capture_date',
                'label' => trans('leads::leads.form.capture_date'),
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
                'id' => 'leads.secondary_email',
                'label' => trans('leads::leads.form.secondary_email'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.lead_company',
                'label' => trans('leads::leads.form.company'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.created_at',
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
                'id' => 'leads.updated_at',
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
                'id' => 'leads.annual_revenue',
                'label' => trans('leads::leads.form.annual_revenue'),
                'type' => 'double',
            ],
            [
                'id' => 'leads.no_of_employees',
                'label' => trans('leads::leads.form.no_of_employees'),
                'type' => 'integer',
            ],
            [
                'id' => 'leads.phone',
                'label' => trans('leads::leads.form.phone'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.mobile',
                'label' => trans('leads::leads.form.mobile'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.fax',
                'label' => trans('leads::leads.form.fax'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.twitter',
                'label' => trans('leads::leads.form.twitter'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.facebook',
                'label' => trans('leads::leads.form.facebook'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.skype',
                'label' => trans('leads::leads.form.skype'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.website',
                'label' => trans('leads::leads.form.website'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.addr_street',
                'label' => trans('leads::leads.form.addr_street'),
                'type' => 'string'
            ]
            ,
            [
                'id' => 'leads.addr_state',
                'label' => trans('leads::leads.form.addr_state'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.addr_country',
                'label' => trans('leads::leads.form.addr_country'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.addr_city',
                'label' => trans('leads::leads.form.addr_city'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.addr_zip',
                'label' => trans('leads::leads.form.addr_zip'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.job_title',
                'label' => trans('leads::leads.form.job_title'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.description',
                'label' => trans('leads::leads.form.description'),
                'type' => 'string'
            ],
            [
                'id' => 'leads.lead_status_id',
                'label' => trans('leads::leads.form.lead_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => LeadStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'leads.lead_source_id',
                'label' => trans('leads::leads.form.lead_source_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => LeadSource::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'leads.lead_industry_id',
                'label' => trans('leads::leads.form.lead_industry_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => LeadIndustry::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'leads.lead_rating_id',
                'label' => trans('leads::leads.form.lead_rating_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => LeadRating::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ]

        ];
    }

    public static function availableColumns()
    {
        return
[
                'full_name' => [
                    'data' => 'full_name',
                    'title' => trans('leads::leads.form.name'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'email' => [
                    'data' => 'email',
                    'title' => trans('leads::leads.form.email'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'capture_date' => [
                    'data' => 'capture_date',
                    'title' => trans('leads::leads.form.capture_date'),
                    'data_type' => 'datetime',
                    'filter_type' => 'bap_date_range_picker'
                ],

                'source' => [
                    'name' => 'leads_dict_source.name',
                    'data' => 'source',
                    'title' => trans('leads::leads.form.source'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'status' => [
                    'name' => 'leads_dict_status.name',
                    'data' => 'status',
                    'title' => trans('leads::leads.form.status'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'industry' => [
                    'name' => 'leads_dict_industry.name',
                    'data' => 'industry',
                    'title' => trans('leads::leads.form.industry'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'rating' => [
                    'name' => 'leads_dict_rating.name',
                    'data' => 'rating',
                    'title' => trans('leads::leads.form.rating'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'lead_company' => [
                    'data' => 'lead_company',
                    'title' => trans('leads::leads.form.company'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'secondary_email' => [
                    'data' => 'secondary_email',
                    'title' => trans('leads::leads.form.secondary_email'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'annual_revenue' => [
                    'data' => 'annual_revenue',
                    'title' => trans('leads::leads.form.annual_revenue'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'no_of_employees' => [
                    'data' => 'no_of_employees',
                    'title' => trans('leads::leads.form.no_of_employees'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'phone' => [
                    'data' => 'phone',
                    'title' => trans('leads::leads.form.phone'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'mobile' => [
                    'data' => 'mobile',
                    'title' => trans('leads::leads.form.mobile'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'fax' => [
                    'data' => 'fax',
                    'title' => trans('leads::leads.form.fax'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'twitter' => [
                    'data' => 'twitter',
                    'title' => trans('leads::leads.form.twitter'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'facebook' => [
                    'data' => 'facebook',
                    'title' => trans('leads::leads.form.facebook'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'skype' => [
                    'data' => 'skype',
                    'title' => trans('leads::leads.form.skype'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'website' => [
                    'data' => 'website',
                    'title' => trans('leads::leads.form.website'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'addr_street' => [
                    'data' => 'addr_street',
                    'title' => trans('leads::leads.form.addr_street'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'addr_state' => [
                    'data' => 'addr_state',
                    'title' => trans('leads::leads.form.addr_state'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'addr_country' => [
                    'data' => 'addr_country',
                    'title' => trans('leads::leads.form.addr_country'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'addr_city' => [
                    'data' => 'addr_city',
                    'title' => trans('leads::leads.form.addr_city'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'addr_zip' => [
                    'data' => 'addr_zip',
                    'title' => trans('leads::leads.form.addr_zip'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'job_title' => [
                    'data' => 'job_title',
                    'title' => trans('leads::leads.form.job_title'),
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
            DataTableHelper::queryOwner($query, $keyword,'leads');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('leads.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('leads.updated_at', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('capture_date', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('leads.capture_date', array($dates[0], $dates[1]));
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
    public function query(Lead $model)
    {

        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('leads_dict_industry','leads.lead_industry_id','=','leads_dict_industry.id')
            ->leftJoin('leads_dict_rating','leads.lead_rating_id','=','leads_dict_rating.id')
            ->leftJoin('leads_dict_status','leads.lead_status_id','=','leads_dict_status.id')
            ->leftJoin('leads_dict_source','leads.lead_source_id','=','leads_dict_source.id')
            ->select(
                'leads.*',
                'leads_dict_industry.name as industry',
                'leads_dict_rating.name as rating',
                'leads_dict_status.name as status',
                'leads_dict_source.name as source'
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
