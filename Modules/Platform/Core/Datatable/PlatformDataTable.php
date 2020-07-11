<?php

namespace Modules\Platform\Core\Datatable;

use Modules\Platform\Core\Helper\DataTableHelper;
use phpDocumentor\Reflection\Types\Self_;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

/**
 * Class PlatformDataTable
 * @package Modules\Platform\Core\Datatable
 */
abstract class PlatformDataTable extends DataTable
{
    protected $sourceRoute;

    protected $editRoute;

    protected $tableId;

    protected $filterRules;

    protected $filterDefinition;

    protected $advancedView;

    public $allowSelect = false;

    public $allowUnlink = false;

    public $allowQuickEdit = false;

    protected $additionalValues = [];

    /**
     * Additional values
     * @param array $values
     */
    public function setAdditionalValues($values = []){
        $this->additionalValues = $values;
    }

    /**
     * Default unlink button
     *
     * @var array
     */
    protected $btnUnlink = [
        'unlink' => [
            'data' => 'unlink',
            'title' => '',
            'data_type' => 'unlink',
            'orderable' => false,
            'searchable' => false,
        ]
    ];

    /**
     * Default checkbox button
     *
     * @var array
     */
    protected $btnCheck_selection = [
        'check_select' => [
            'data' => 'check_select',
            'title' => '',
            'data_type' => 'check_select',
            'orderable' => false,
            'searchable' => false,
        ]
    ];

    /**
     * Default quick edit button
     *
     * @var array
     */
    protected $btnQuick_edit = [
        'quick_edit' => [
            'data' => 'quick_edit',
            'title' => '',
            'data_type' => 'quick_edit',
            'orderable' => false,
            'searchable' => false,

        ]
    ];


    /**
     * Available query filters definition
     * @return array
     */
    public static function availableQueryFilters(){
           return [];
    }

    /**
     * Available columns
     */
    public static function availableColumns(){
           return [];
    }

    protected function setFilterDefinition()
    {
        $this->filterDefinition = self::availableQueryFilters();
    }

    public function getFilterDefinition()
    {
        return $this->filterDefinition;
    }

    /**
     * @param $route
     */
    public function setAjaxSource($route)
    {
        $this->sourceRoute = $route;
    }

    public function setTableId($tableId)
    {
        $this->tableId = $tableId;
    }

    /**
     * Apply rules from jquery rules builder
     *
     * @param $rules
     */
    public function applyFilterRules($rules)
    {
        $this->filterRules = $rules;
    }

    public function applyAdvancedView($view,$moduleName){

        if(!empty($view->defined_columns)){

            $columns = unserialize($view->defined_columns);
            $allColumns = $this->availableColumns();

            if(!empty($columns)) {

                foreach ($columns as $key => $value) {
                    $this->advancedView[$value] = $allColumns[$value];
                }
            }else{
                $this->advancedView = $allColumns;
            }

            if(!empty($view->filter_rules) && empty($this->filterRules)){
                $this->filterRules = $view->filter_rules;
            }

        }

        // base on $view && $moduleName
        // find check if user can access this if not return default view.

    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function builder()
    {
        $this->setFilterDefinition();
        $builder = parent::builder();

        $builder->hasQueryFilters = false;

        if (!empty($this->filterDefinition)) {
            $builder->hasQueryFilters = true;
        }

        if ($this->tableId != '') {
            $builder = $builder->setTableId($this->tableId);
        }
        if ($this->sourceRoute != '') {
            $builder = $builder->ajax($this->sourceRoute);
        }


        return $builder;
    }

    /**
     * @param EloquentDataTable $table
     * @param $route
     * @param null $prefix
     */
    public function applyLinks(EloquentDataTable $table, $route, $prefix = null)
    {
        $rawColumns = [];

        $table->addRowAttr('record-id', function ($record) {
            return $record->id;
        });

        $table->addRowAttr('record-type', function ($record) {
            return get_class($record);
        });

        foreach ($this->getColumns() as $column => $properties) {
            $rawColumns[] = $column;

            $table->editColumn($column, function ($record) use ($column, $properties, $route, $prefix) {

                if ($properties['data_type'] == 'unlink') {
                    $recordId = $record->id;

                    $view = view('core::crud.relation.unlink');
                    $view->with('entityId', $this->entityId);
                    $view->with('relationEntityId', $recordId);
                    $view->with('unlink_route', $this->unlinkRoute);

                    return $view;
                }

                if ($properties['data_type'] == 'quick_edit') {
                    $recordId = $record->id;

                    $view = view('core::crud.relation.quick_edit');
                    $view->with('entityId', $this->entityId);
                    $view->with('relationEntityId', $recordId);
                    $view->with('edit_route', $this->editRoute);

                    return $view;
                }

                if ($properties['data_type'] == 'check_select') {
                    $recordId = $record->id;

                    return '<input type="checkbox" name="selection[]" id="'.$prefix.'checkbox_'.$recordId.'" class="call-checkbox filled-in chk-col-blue-grey" value="'.$recordId.'" /><label class="checkbox" for="'.$prefix.'checkbox_'.$recordId.'"></label>';
                }


                return DataTableHelper::renderLink($column, $record, $properties, $route, $prefix);
            });
        }

        $table->rawColumns($rawColumns);
    }

    /**
     * @return array
     */
    protected function getColumns()
    {
        return [];
    }

}
