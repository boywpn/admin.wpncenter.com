<?php

namespace Modules\Core\BanksPartners\Datatables;

use Modules\Core\Banks\Entities\Banks;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\Partners\Entities\Partners;
use Modules\Member\Members\Entities\MembersStatus;
use Modules\Platform\Core\Datatable\PlatformDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;

class BanksPartnersDatatable extends PlatformDataTable
{
    const SHOW_URL_ROUTE = 'core.bankspartners.show';

    protected $editRoute = 'core.bankspartners.edit';

    public static function availableColumns()
    {
        return
            [
                'name' => [
                    'data' => 'name',
                    'title' => trans('core/bankspartners::bankspartners.table.name'),
                    'data_type' => 'text',
                    'filter_type' => 'text'
                ],
                'bank_account' => [
                    'name' => 'core_banks.id',
                    'data' => 'bank_account',
                    'title' => trans('core/bankspartners::bankspartners.form.bank_id'),
                    'data_type' => 'text',
                    'filter_type' => 'select',
                    'select_type' => 'select2',
                    'select_type_options' => [
                        'theme' => "bootstrap",
                        'width' => '100%'
                    ],
                    'filter_data' => Banks::getSelectOption()
                ],
                'partner' => [
                    'name' => 'core_partners.id',
                    'data' => 'partner',
                    'title' => trans('core/bankspartners::bankspartners.form.partner_id'),
                    'data_type' => 'text',
                    'filter_type' => 'select',
                    'select_type' => 'select2',
                    'select_type_options' => [
                        'theme' => "bootstrap",
                        'width' => '100%'
                    ],
                    'filter_data' => Partners::getSelectOption()
                ],
                'member_status' => [
                    'name' => 'member_members_dict_status.id',
                    'data' => 'member_status',
                    'title' => trans('core/bankspartners::bankspartners.form.member_status_id'),
                    'data_type' => 'text',
                    'filter_type' => 'select',
                    'select_type' => 'select2',
                    'select_type_options' => [
                        'theme' => "bootstrap",
                        'width' => '100%'
                    ],
                    'filter_data' => MembersStatus::getSelectOption()
                ],
                'is_active' => [
                    'title' => trans('core/bankspartners::bankspartners.table.is_active'),
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
                ]
            ];
    }

    protected function setFilterDefinition()
    {
        $this->filterDefinition = self::availableQueryFilters();
    }

    /**
     * DataTable definition
     *
     * @param Object $query Query object
     *
     * @return EloquentDataTable
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);


        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE);

        $dataTable->editColumn('is_active', function ($record) {
            return "<span class='badge " . StringHelper::badgeHelper($record->is_active, BanksPartners::COLORS) . "'>".(($record->is_active) ? trans('core::core.yes') : trans('core::core.no'))."</span>";
        });

        $dataTable->editColumn('bank_account', function ($record) {
            return delMiltiSpace($record->bank_account)." [".$record->bank_code." ".$record->bank_number."]";
        });

        $dataTable->editColumn('member_status', function ($record) {
            return StringHelper::badgeFullHelper($record->member_status_id, MembersStatus::badgeTable());
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
     * @param Account $model
     *
     * @return $this
     */
    public function query(BanksPartners $model)
    {

        $query = $model->newQuery()
            ->leftJoin('member_members_dict_status', 'core_banks_partners.member_status_id', '=', 'member_members_dict_status.id')
            ->leftJoin('core_partners', 'core_banks_partners.partner_id', '=', 'core_partners.id')
            ->leftJoin('core_banks', 'core_banks_partners.bank_id', '=', 'core_banks.id')
            ->leftJoin('banks', 'core_banks.bank_id', '=', 'banks.id')
            ->whereNull('core_banks.deleted_at')
            ->select(
                'core_banks_partners.*',
                'member_members_dict_status.name as member_status',
                'core_partners.name as partner',
                'core_banks.account as bank_account',
                'core_banks.number as bank_number',
                'banks.name as bank_name',
                'banks.code as bank_code'
            );

        return $query;

    }

    /**
     * @return Builder
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
