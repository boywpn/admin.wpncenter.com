<?php

namespace Modules\Accounts\Datatables\Tabs;

use Modules\Campaigns\Datatables\CampaignDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Campaigns\Entities\CampaignStatus;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class AccountCampaignsDatatable
 * @package Modules\Accounts\Datatables\Tabs
 */
class AccountCampaignsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'campaigns.campaigns.show';

    protected $unlinkRoute = 'accounts.campaigns.unlink';

    protected $editRoute = 'campaigns.campaigns.edit';

    public static function availableColumns()
    {
        return CampaignDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return CampaignDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'campaigns_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword, 'campaigns');
        });

        $dataTable->editColumn('status', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->status_id, CampaignStatus::COLORS) . "'>$record->status</span>";
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
        $query = $model->with('owner')
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
            ->setTableId('AccountCampaignsDatatable' . $this->tableSuffix)
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

        $columns = CampaignDatatable::availableColumns();

        $result = [];

        if ($this->allowSelect) {
            $result =  $this->btnCheck_selection;
        }
        if ($this->allowUnlink) {
            $result =  $this->btnUnlink ;
        }
        if ($this->allowUnlink) {
            $result =  $result + $this->btnQuick_edit; ;
        }

        $result = $result + $columns;

        return $result;
    }
}
