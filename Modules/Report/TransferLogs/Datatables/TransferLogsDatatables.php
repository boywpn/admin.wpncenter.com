<?php

namespace Modules\Report\TransferLogs\Datatables;

use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Report\TransferLogs\Entities\TransferLogs;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class PaymentsDatatable
 * @package Modules\Payments\Datatables
 */
class TransferLogsDatatables extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'report.transferlogs.show';

    protected $editRoute = 'report.transferlogs.edit';


    public static function availableColumns()
    {
        return [
            'request_time' => [
                'data' => 'request_time',
                'title' => trans('report/transferlogs::transfer.table.request_time'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
                'searchable' => false,
            ],
            'job_id' => [
                'data' => 'job_id',
                'title' => trans('report/transferlogs::transfer.table.job_id'),
                'data_type' => 'text',
                'filter_type' => 'text',
                'searchable' => false,
            ],
            'order_code' => [
                'data' => 'order_code',
                'title' => trans('report/transferlogs::transfer.table.order_code'),
                'data_type' => 'text',
                'filter_type' => 'text',
                'searchable' => false,
            ],
            'username' => [
                'data' => 'username',
                'title' => trans('report/transferlogs::transfer.table.username_id'),
                'data_type' => 'text',
                'filter_type' => 'text',
                'searchable' => false,
            ],
            'amount' => [
                'data' => 'amount',
                'title' => trans('report/transferlogs::transfer.table.amount'),
                'data_type' => 'text',
                'filter_type' => 'text',
                'searchable' => false,
            ],
            'credit_bf' => [
                'data' => 'credit_bf',
                'title' => trans('report/transferlogs::transfer.table.credit_bf'),
                'data_type' => 'text',
                'filter_type' => 'text',
                'searchable' => false,
            ],
            'credit_af' => [
                'data' => 'credit_af',
                'title' => trans('report/transferlogs::transfer.table.credit_af'),
                'data_type' => 'text',
                'filter_type' => 'text',
                'searchable' => false,
            ],
            'created_at' => [
                'data' => 'created_at',
                'title' => trans('core::core.table.created_at'),
                'data_type' => 'datetime',
                'filter_type' => 'bap_date_range_picker',
                'searchable' => false,
            ],
        ];
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
    public function query(TransferLogs $model)
    {
        $query = $model->newQuery();

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
                'filterRules' => $this->filterRules,
                'headerFilters' => false,
                'order' => [[ 0, 'desc' ]],
                'buttons' => DataTableHelper::buttons(),
                'regexp' => true,
                'searching' => false,
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

//        if ($this->allowSelect) {
//            $result =  $this->btnCheck_selection;
//        }
//
//        if ($this->allowUnlink) {
//            $result =  $this->btnUnlink ;
//        }
//
//        if ($this->allowQuickEdit) {
//            $result =  $result + $this->btnQuick_edit; ;
//        }

        $result = $result + $columns;

        return $result;
    }
}
