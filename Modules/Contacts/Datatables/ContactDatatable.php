<?php

namespace Modules\Contacts\Datatables;

use Modules\Contacts\Entities\Contact;
use Modules\Contacts\Entities\ContactSource;
use Modules\Contacts\Entities\ContactStatus;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class ContactDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'contacts.contacts.show';

    protected $editRoute = 'contacts.contacts.edit';

    public static function availableColumns()
    {
        return [
            'full_name' => [
                'data' => 'full_name',
                'title' => trans('contacts::contacts.form.full_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'status' => [
                'name' => 'contacts_dict_status.name',
                'data' => 'status',
                'title' => trans('contacts::contacts.form.status'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'source' => [
                'name' => 'contacts_dict_source.name',
                'data' => 'source',
                'title' => trans('contacts::contacts.form.source'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'job_title' => [
                'data' => 'job_title',
                'title' => trans('contacts::contacts.form.job_title'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'department' => [
                'data' => 'department',
                'title' => trans('contacts::contacts.form.department'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'phone' => [
                'data' => 'phone',
                'title' => trans('contacts::contacts.form.phone'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'mobile' => [
                'data' => 'mobile',
                'title' => trans('contacts::contacts.form.mobile'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'email' => [
                'data' => 'email',
                'title' => trans('contacts::contacts.form.email'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'secondary_email' => [
                'data' => 'secondary_email',
                'title' => trans('contacts::contacts.form.secondary_email'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'fax' => [
                'data' => 'fax',
                'title' => trans('contacts::contacts.form.fax'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'assistant_name' => [
                'data' => 'assistant_name',
                'title' => trans('contacts::contacts.form.assistant_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'assistant_phone' => [
                'data' => 'assistant_phone',
                'title' => trans('contacts::contacts.form.assistant_phone'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'street' => [
                'data' => 'street',
                'title' => trans('contacts::contacts.form.street'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'state' => [
                'data' => 'state',
                'title' => trans('contacts::contacts.form.state'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'country' => [
                'data' => 'country',
                'title' => trans('contacts::contacts.form.country'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'zip_code' => [
                'data' => 'zip_code',
                'title' => trans('contacts::contacts.form.zip_code'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'city' => [
                'data' => 'city',
                'title' => trans('contacts::contacts.form.city'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('contacts::contacts.form.account_name'),
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
                'id' => 'contacts.full_name',
                'label' => trans('contacts::contacts.form.full_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.tags',
                'label' => trans('contacts::contacts.form.tags'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.job_title',
                'label' => trans('contacts::contacts.form.job_title'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.department',
                'label' => trans('contacts::contacts.form.department'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.phone',
                'label' => trans('contacts::contacts.form.phone'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.mobile',
                'label' => trans('contacts::contacts.form.mobile'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.email',
                'label' => trans('contacts::contacts.form.email'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.secondary_email',
                'label' => trans('contacts::contacts.form.secondary_email'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.fax',
                'label' => trans('contacts::contacts.form.fax'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.assistant_name',
                'label' => trans('contacts::contacts.form.assistant_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.assistant_phone',
                'label' => trans('contacts::contacts.form.assistant_phone'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.street',
                'label' => trans('contacts::contacts.form.street'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.state',
                'label' => trans('contacts::contacts.form.state'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.country',
                'label' => trans('contacts::contacts.form.country'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.zip_code',
                'label' => trans('contacts::contacts.form.zip_code'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.city',
                'label' => trans('contacts::contacts.form.city'),
                'type' => 'string',
            ],


            [
                'id' => 'accounts.name',
                'label' => trans('contacts::contacts.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.contact_status_id',
                'label' => trans('contacts::contacts.form.contact_status_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ContactStatus::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'contacts.contact_source_id',
                'label' => trans('contacts::contacts.form.contact_source_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => ContactSource::pluck('name', 'id'),
                'operators' => [
                    'in',
                    'not_in',
                    'is_null',
                    'is_not_null'
                ]
            ],
            [
                'id' => 'contacts.created_at',
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
                'id' => 'contacts.updated_at',
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

        $dataTable->editColumn('status', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->status_id, ContactStatus::COLORS) . "'>$record->status</span>";
        });

        $dataTable->editColumn('phone', function ($record) {
            return "<span class='badge bg-grey'><i class='fa fa-mobile-phone'></i> $record->phone</span>";
        });
        $dataTable->editColumn('email', function ($record) {
            return "<span class='badge bg-grey'><i class='fa fa-envelope-o'></i> $record->email</span>";
        });

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'contacts');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('contacts.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('contacts.updated_at', array($dates[0], $dates[1]));
            }
        });


        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contact $model)
    {

        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('contacts_dict_status', 'contacts.contact_status_id', '=', 'contacts_dict_status.id')
            ->leftJoin('contacts_dict_source', 'contacts.contact_source_id', '=', 'contacts_dict_source.id')
            ->leftJoin('accounts', 'contacts.account_id', '=', 'accounts.id')
            ->select([
                'contacts.*',
                'contacts_dict_status.id as status_id',
                'contacts_dict_status.name as status',
                'contacts_dict_source.id as source_id',
                'contacts_dict_source.name as source',
                'accounts.name as account_name',
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
