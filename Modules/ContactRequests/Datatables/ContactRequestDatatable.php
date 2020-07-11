<?php

namespace Modules\ContactRequests\Datatables;


use Modules\ContactRequests\Entities\ContactReason;
use Modules\ContactRequests\Entities\ContactRequest;
use Modules\ContactRequests\Entities\ContactRequestStatus;
use Modules\ContactRequests\Entities\PreferredContactMethod;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class ContactRequestDatatable extends PlatformDataTable
{

    const SHOW_URL_ROUTE = 'contactrequests.contactrequests.show';

    protected $editRoute =  'contactrequests.contactrequests.edit';


    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'contact_request.first_name',
                'label' => trans('contactrequests::contactrequests.form.first_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.last_name',
                'label' => trans('contactrequests::contactrequests.form.last_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.organization_name',
                'label' => trans('contactrequests::contactrequests.form.organization_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.phone_number',
                'label' => trans('contactrequests::contactrequests.form.phone_number'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.email',
                'label' => trans('contactrequests::contactrequests.form.email'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.other_contact_method',
                'label' => trans('contactrequests::contactrequests.form.other_contact_method'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.custom_subject',
                'label' => trans('contactrequests::contactrequests.form.custom_subject'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.notes',
                'label' => trans('contactrequests::contactrequests.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'contact_request.contact_date',
                'label' => trans('contactrequests::contactrequests.form.contact_date'),
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
                'id' => 'contact_request.next_contact_date',
                'label' => trans('contactrequests::contactrequests.form.next_contact_date'),
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
                'id' => 'contact_request.status_id',
                'label' => trans('contactrequests::contactrequests.form.status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ContactRequestStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'contact_request.preferred_id',
                'label' => trans('contactrequests::contactrequests.form.preferred_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => PreferredContactMethod::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'contact_request.contact_reason_id',
                'label' => trans('contactrequests::contactrequests.form.preferred_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ContactReason::pluck('name', 'id'),
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
            'first_name' => [
                'data' => 'first_name',
                'title' => trans('contactrequests::contactrequests.form.first_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'last_name' => [
                'data' => 'last_name',
                'title' => trans('contactrequests::contactrequests.form.last_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'status' => [
                'name' => 'contact_request_dict_contact_status.name',
                'data' => 'status',
                'title' => trans('contactrequests::contactrequests.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'method' => [
                'name' => 'contact_request_dict_contact_method.name',
                'data' => 'method',
                'title' => trans('contactrequests::contactrequests.form.method'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'reason' => [
                'name' => 'contact_request_dict_contact_reason.name',
                'data' => 'method',
                'title' => trans('contactrequests::contactrequests.form.reason'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],

            'organization_name' => [
                'data' => 'organization_name',
                'title' => trans('contactrequests::contactrequests.form.organization_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'phone_number' => [
                'data' => 'phone_number',
                'title' => trans('contactrequests::contactrequests.form.phone_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'email' => [
                'data' => 'email',
                'title' => trans('contactrequests::contactrequests.form.email'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'other_contact_method' => [
                'data' => 'other_contact_method',
                'title' => trans('contactrequests::contactrequests.form.other_contact_method'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'custom_subject' => [
                'data' => 'custom_subject',
                'title' => trans('contactrequests::contactrequests.form.custom_subject'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'contact_date' => [
                'data' => 'contact_date',
                'title' => trans('contactrequests::contactrequests.form.contact_date'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
            ],
            'next_contact_date' => [
                'data' => 'next_contact_date',
                'title' => trans('contactrequests::contactrequests.form.next_contact_date'),
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
            DataTableHelper::queryOwner($query, $keyword,'contact_request');
        });

        $dataTable->filterColumn('contact_date', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('contact_request.contact_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('next_contact_date', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('contact_request.next_contact_date', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('contact_request.created_at', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('updated_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('contact_request.updated_at', array($dates[0], $dates[1]));
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
    public function query(ContactRequest $model)
    {
        $query =  $model
            ->newQuery()
            ->with('owner')
            ->leftJoin('contact_request_dict_contact_status', 'contact_request.status_id', '=', 'contact_request_dict_contact_status.id')
            ->leftJoin('contact_request_dict_contact_method', 'contact_request.preferred_id', '=', 'contact_request_dict_contact_method.id')
            ->leftJoin('contact_request_dict_contact_reason', 'contact_request.contact_reason_id', '=', 'contact_request_dict_contact_reason.id')
            ->with('owner')->select([
                'contact_request.*',
                'contact_request_dict_contact_status.name as status',
                'contact_request_dict_contact_method.name as method',
                'contact_request_dict_contact_reason.name as reason',
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
