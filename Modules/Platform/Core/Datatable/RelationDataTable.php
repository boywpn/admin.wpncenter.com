<?php

namespace Modules\Platform\Core\Datatable;

use Modules\Platform\Core\Helper\DataTableHelper;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class RelationDataTable
 * @package Modules\Platform\Core\Datatable
 */
abstract class RelationDataTable extends PlatformDataTable
{


    /**
     * @var
     */
    protected $entityId;

    /**
     * @var
     */
    protected $entityClass;

    protected $route;

    public $allowSelect = false;

    public $allowUnlink = false;

    public $allowDelete = false;

    protected $unlinkRoute;

    protected $deleteRoute;

    protected $editRoute;

    protected $tableSuffix;

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
     * Default delete button
     * @var array
     */
    protected $btnDelete = [
        'delete' => [
            'data' => 'delete',
            'title' => '',
            'data_type' => 'delete',
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
     * Setup Select Model
     */
    public function selectMode()
    {
        $this->tableSuffix = "_Select";
    }

    public function applyLinks(EloquentDataTable $table, $route, $prefix = null)
    {
        $rawColumns = [];

        foreach ($this->getColumns() as $column => $properties) {
            $rawColumns[] = $column;

            $table->editColumn($column, function ($record) use ($column,$properties,$route,$prefix) {
                if ($properties['data_type'] == 'unlink') {
                    $recordId = $record->id;

                    $view = view('core::crud.relation.unlink');
                    $view->with('entityId', $this->entityId);
                    $view->with('relationEntityId', $recordId);
                    $view->with('unlink_route', $this->unlinkRoute);

                    return $view;
                }

                if ($properties['data_type'] == 'delete') {
                    $recordId = $record->id;

                    $view = view('core::crud.relation.delete');
                    $view->with('entityId', $this->entityId);
                    $view->with('relationEntityId', $recordId);
                    $view->with('delete_route', $this->deleteRoute);

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

                return DataTableHelper::renderLink($column, $record, $properties, $route);
            });
        }

        $table->rawColumns($rawColumns);
    }

    /**
     * @param $columnNumber
     * @return mixed
     */
    protected function countFilterColumn($columnNumber)
    {
        if ($this->allowSelect || $this->allowUnlink || $this->allowQuickEdit) {
            return $columnNumber+1;
        }
        return $columnNumber;
    }

    /**
     * @param $entityClass
     * @param $entityId
     * @param $route
     */
    public function setEntityData($entityClass, $entityId, $route)
    {
        $this->entityClass = $entityClass;
        $this->entityId = $entityId;
        $this->route = $route;
    }
}
