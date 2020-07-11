<?php

namespace Modules\Core\Promotions\Datatables;

use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Promotions\Entities\Promotions;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class PaymentsDatatable
 * @package Modules\Payments\Datatables
 */
class PromotionsDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'core.promotions.show';

    protected $editRoute = 'core.promotions.edit';


    public static function availableColumns()
    {
        return [
            'name' => [
                'data' => 'name',
                'title' => trans('core/promotions::promotions.form.name'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'partner' => [
                'name' => 'core_partners.id',
                'data' => 'partner',
                'title' => trans('core/promotions::promotions.form.partner_id'),
                'data_type' => 'text',
                'filter_type' => 'select',
                'select_type' => 'select2',
                'select_type_options' => [
                    'theme' => "bootstrap",
                    'width' => '100%'
                ],
                'filter_data' => Partners::getSelectOption()
            ],
            'percent' => [
                'data' => 'percent',
                'title' => trans('core/promotions::promotions.form.percent'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'amount' => [
                'data' => 'amount',
                'title' => trans('core/promotions::promotions.form.amount'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'min_deposit' => [
                'data' => 'min_deposit',
                'title' => trans('core/promotions::promotions.form.min_deposit'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'max_deposit' => [
                'data' => 'max_deposit',
                'title' => trans('core/promotions::promotions.form.max_deposit'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'max_value' => [
                'data' => 'max_value',
                'title' => trans('core/promotions::promotions.form.max_value'),
                'data_type' => 'text',
                'filter_type' => 'text'
            ],
            'is_front' => [
                'title' => trans('core/promotions::promotions.table.is_front'),
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
            'have_ref' => [
                'title' => trans('core/promotions::promotions.table.have_ref'),
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
                'title' => trans('core/promotions::promotions.table.is_active'),
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
            'expired_at' => [
                'data' => 'expired_at',
                'title' => trans('core/promotions::promotions.table.expired_at'),
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

        $dataTable->editColumn('is_active', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, Promotions::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('is_front', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_front, Promotions::COLORS) . "'>".(($record->is_front) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });
        $dataTable->editColumn('have_ref', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->have_ref, Promotions::COLORS) . "'>".(($record->have_ref) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
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
    public function query(Promotions $model)
    {
        $query = $model->newQuery()
            ->leftJoin('core_partners', 'core_promotions.partner_id', '=', 'core_partners.id')
            ->select(
                'core_promotions.*',
                'core_partners.name as partner'
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
