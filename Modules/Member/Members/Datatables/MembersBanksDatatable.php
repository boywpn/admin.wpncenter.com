<?php

namespace Modules\Member\Members\Datatables;

use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class PaymentsDatatable
 * @package Modules\Payments\Datatables
 */
class MembersBanksDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'member.members.banks.show';

    protected $editRoute = 'member.members.banks.edit';


    public static function availableColumns()
    {
        return [
            'bank' => [
                'name' => 'banks.name',
                'data' => 'bank_name',
                'title' => trans('member/members::banks.table.bank_id'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'bank_account' => [
                'data' => 'bank_account',
                'title' => trans('member/members::banks.table.bank_account'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'bank_number' => [
                'data' => 'bank_number',
                'title' => trans('member/members::banks.table.bank_number'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'is_main' => [
                'title' => trans('member/members::banks.table.is_main'),
                'data_type' => 'boolean',
                'filter_type' => 'select',
                'select_type' => 'select2',
                'select_type_options' => [
                    'theme' => "bootstrap",
                    'width' => '100%'
                ],
                'filter_data' => [
                    [
                        'value' => 1,
                        'label' => trans('core::core.yes')
                    ],
                    [
                        'value' => 0,
                        'label' => trans('core::core.no')
                    ]
                ]
            ],
            'is_active' => [
                'title' => trans('member/members::banks.table.is_active'),
                'data_type' => 'boolean',
                'filter_type' => 'select',
                'select_type' => 'select2',
                'select_type_options' => [
                    'theme' => "bootstrap",
                    'width' => '100%'
                ],
                'filter_data' => [
                    [
                        'value' => 1,
                        'label' => trans('core::core.yes')
                    ],
                    [
                        'value' => 0,
                        'label' => trans('core::core.no')
                    ]
                ]
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
            ->columns($this->getColumns())
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
