<?php

namespace Modules\Leads\Datatables\Tabs;


use Modules\ContactEmails\Entities\ContactEmail;
use Modules\LeadEmails\Entities\LeadEmail;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Yajra\DataTables\EloquentDataTable;

class LeadEmailDatatable extends RelationDataTable
{

    const SHOW_URL_ROUTE = 'leademails.leademails.show';

    protected $unlinkRoute = 'leads.leademails.unlink';

    protected $deleteRoute = 'leads.leademails.delete';

    protected $editRoute = 'leademails.leademails.edit';

    public static function availableColumns()
    {
        return [
            'email' => [
                'data' => 'email',
                'title' => trans('contactemails::contactemails.table.email'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'is_default' => [
                'data' => 'is_default',
                'title' => trans('contactemails::contactemails.table.is_default'),
                'data_type' => 'boolean',
                'filter_type' => 'text'
            ],
            'is_active' => [
                'data' => 'is_active',
                'title' => trans('contactemails::contactemails.table.is_active'),
                'data_type' => 'boolean',
                'filter_type' => 'text'
            ],
            'is_marketing' => [
                'data' => 'is_marketing',
                'title' => trans('contactemails::contactemails.table.is_marketing'),
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
        ];
    }

    public static function availableQueryFilters()
    {
        return [

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


        $this->applyLinks($dataTable, '', 'leads_emails_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'lead_email');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('lead_email.created_at', array($dates[0], $dates[1]));
            }
        });

        $dataTable->filterColumn('is_default', function ($query, $keyword) {

            if ($keyword == 'yes') {
                $query->where('lead_email.is_default', 1);
            } else {
                $query->where('lead_email.is_default', 0);
            }
        });
        $dataTable->filterColumn('is_active', function ($query, $keyword) {

            if ($keyword == 'yes') {
                $query->where('lead_email.is_active', 1);
            } else {
                $query->where('lead_email.is_active', 0);
            }
        });
        $dataTable->filterColumn('is_active', function ($query, $keyword) {

            if ($keyword == 'yes') {
                $query->where('lead_email.is_active', 1);
            } else {
                $query->where('lead_email.is_active', 0);
            }
        });
        $dataTable->filterColumn('is_marketing', function ($query, $keyword) {

            if ($keyword == 'yes') {
                $query->where('lead_email.is_marketing', 1);
            } else {
                $query->where('lead_email.is_marketing', 0);
            }
        });

        $dataTable->filterColumn('updated_at', function ($query, $keyword) {

            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('lead_email.updated_at', array($dates[0], $dates[1]));
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
    public function query(LeadEmail $model)
    {
        return $model->newQuery()->select();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('LeadEmailsDatatable' . $this->tableSuffix)
            ->columns($this->getColumns())
            ->minifiedAjax(route($this->route, ['entityId' => $this->entityId]))
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

        $this->allowUnlink = false;
        $this->allowDelete = true;

        $result = $this->btnDelete + $this->btnQuick_edit;

        $result = $result + self::availableColumns();

        return $result;

    }

}
