<?php

namespace Modules\Member\Members\Datatables\Tabs;

use Modules\Core\Boards\Datatables\BoardsUsersDatatable;
use Modules\Core\Boards\Entities\BoardsUsers;
use Modules\Core\Username\Datatables\UsernameDatatable;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Datatables\MembersBanksDatatable;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class AccountTicketsDatatable
 * @package Modules\Accounts\Datatables\Tabs
 */
class MembersBanksDatatableTab extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'member.members.banks.show';

    protected $unlinkRoute = 'member.members.banks.unlink';

    protected $editRoute = 'member.members.banks.edit';

    public static function availableColumns()
    {
        return MembersBanksDatatable::availableColumns();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'banks_');

        $dataTable->editColumn('is_main', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_main, MembersBanks::COLORS) . "'>".(($record->is_main) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });

        $dataTable->editColumn('is_active', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, MembersBanks::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('updated_at', array($dates[0], $dates[1]));
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
    public function query(MembersBanks $model)
    {
        $query = $model->newQuery()
            ->leftJoin('banks', 'member_members_banks.bank_id', '=', 'banks.id')
            ->select(
                'member_members_banks.*',
                'banks.name as bank_name'
            );

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
            ->setTableId('MembersBanksDatatable' . $this->tableSuffix)
            ->columns($this->getColumns())
            ->minifiedAjax(route($this->route, ['entityId' => $this->entityId]))
            ->setTableAttribute('class', 'table table-hover')
            ->parameters([
                'dom' => 'lBfrtip',
                'responsive' => false,
                'stateSave' => true,
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

        $columns = MembersBanksDatatableTab::availableColumns();

        $result = [];

        if ($this->allowSelect) {
            $result =  $this->btnCheck_selection;
        }
        if ($this->allowUnlink) {
            $result =  $this->btnUnlink ;
        }
        if ($this->allowUnlink) {
            $result =  $result + $this->btnQuick_edit;
        }

        $result = $result + $columns;

        return $result;
    }
}
