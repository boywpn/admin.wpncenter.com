<?php

namespace Modules\Core\Games\Datatables\Tabs;

use Modules\Core\Boards\Datatables\BoardsUsersDatatable;
use Modules\Core\Boards\Entities\BoardsUsers;
use Modules\Core\Games\Datatables\GamesTypesDatatable;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class AccountTicketsDatatable
 * @package Modules\Accounts\Datatables\Tabs
 */
class GamesTypesDatatableTab extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'core.games-types.show';

    protected $unlinkRoute = 'core.games.types.unlink';

    protected $editRoute = 'core.games-types.edit';

    public static function availableColumns()
    {
        return GamesTypesDatatable::availableColumns();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'types_');

        $dataTable->editColumn('is_active', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, GamesTypes::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('is_commission', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_commission, GamesTypes::COLORS) . "'>".(($record->is_commission) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
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
    public function query(GamesTypes $model)
    {

        $query = $model->newQuery()
            ->leftJoin('core_games', 'core_games_types.game_id', '=', 'core_games.id')
            ->select(
                'core_games_types.*',
                'core_games.name as game_name',
                'core_games.code as game_code'
            );

        if (!empty($this->filterRules)) {
            $queryBuilderParser = new QueryBuilderParser();
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
            ->setTableId('GamesTypesDatatable' . $this->tableSuffix)
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

        $columns = GamesTypesDatatable::availableColumns();

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
