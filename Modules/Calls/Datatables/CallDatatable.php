<?php

namespace Modules\Calls\Datatables;


use Modules\Calls\Entities\Call;
use Modules\Calls\Entities\DirectionType;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

class CallDatatable extends PlatformDataTable
{

    const SHOW_URL_ROUTE = 'calls.calls.show';

    protected $editRoute = 'calls.calls.edit';

    public static function availableQueryFilters()
    {
        return [
            [
                'id' => 'calls.subject',
                'label' => trans('calls::calls.form.subject'),
                'type' => 'string',
            ],
            [
                'id' => 'calls.phone_number',
                'label' => trans('calls::calls.form.phone_number'),
                'type' => 'string',
            ],
            [
                'id' => 'accounts.name',
                'label' => trans('calls::calls.form.account_name'),
                'type' => 'string',
            ],
            [
                'id' => 'contacts.full_name',
                'label' => trans('calls::calls.form.contact_name'),
                'type' => 'string',
            ],
            [
                'id' => 'leads.full_name',
                'label' => trans('calls::calls.form.lead_name'),
                'type' => 'string',
            ],
            [
                'id' => 'calls.duration',
                'label' => trans('calls::calls.form.duration'),
                'type' => 'string',
            ],
            [
                'id' => 'calls.notes',
                'label' => trans('calls::calls.form.notes'),
                'type' => 'string',
            ],
            [
                'id' => 'calls.call_date',
                'label' => trans('calls::calls.form.call_date'),
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
                'id' => 'calls.direction_id',
                'label' => trans('calls::calls.form.direction_id'),
                'type' => 'integer',
                'input' => 'select',
                'multiple' => true,
                'plugin' => 'select2',
                'plugin_config' => [
                    'multiple' => 'multiple',
                    'width' => '300px',
                ],
                'values' => DirectionType::pluck('name', 'id'),
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
            'subject' => [
                'data' => 'subject',
                'title' => trans('calls::calls.form.subject'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'direction' => [
                'name' => 'calls_dict_direction.name',
                'data' => 'direction',
                'title' => trans('calls::calls.form.direction'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'account_name' => [
                'name' => 'accounts.name',
                'data' => 'account_name',
                'title' => trans('calls::calls.form.account_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'contact_name' => [
                'name' => 'contacts.full_name',
                'data' => 'contact_name',
                'title' => trans('calls::calls.form.contact_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'lead_name' => [
                'name' => 'leads.full_name',
                'data' => 'lead_name',
                'title' => trans('calls::calls.form.lead_name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'phone_number' => [
                'data' => 'phone_number',
                'title' => trans('calls::calls.form.phone_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'duration' => [
                'data' => 'duration',
                'title' => trans('calls::calls.form.duration'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'call_date' => [
                'data' => 'call_date',
                'title' => trans('calls::calls.form.call_date'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker'
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
            DataTableHelper::queryOwner($query, $keyword, 'calls');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('calls.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('call_date', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('calls.call_date', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('calls.updated_at', array($dates[0], $dates[1]));
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
    public function query(Call $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('calls_dict_direction', 'calls.direction_id', '=', 'calls_dict_direction.id')
            ->leftJoin('accounts', 'calls.account_id', '=', 'accounts.id')
            ->leftJoin('contacts', 'calls.contact_id', '=', 'contacts.id')
            ->leftJoin('leads', 'calls.lead_id', '=', 'leads.id')
            ->select(
                'calls.*',
                'calls_dict_direction.name as direction',
                'accounts.name as account_name',
                'contacts.full_name as contact_name',
                'leads.full_name as lead_name'
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
