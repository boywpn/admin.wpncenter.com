<?php

namespace Modules\Accounts\Datatables\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Documents\Datatables\DocumentDatatable;
use Modules\Documents\Entities\Document;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\RelationDataTable;
use Modules\Platform\Core\Helper\DataTableHelper;
use Modules\Platform\Core\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class AccountDocumentsDatatable
 * @package Modules\Accounts\Datatables\Tabs
 */
class AccountDocumentsDatatable extends RelationDataTable
{
    const SHOW_URL_ROUTE = 'documents.documents.show';

    protected $unlinkRoute = 'accounts.documents.unlink';

    protected $editRoute   = 'documents.documents.edit';

    public static function availableColumns()
    {
        return DocumentDatatable::availableColumns();
    }

    public static function availableQueryFilters()
    {
        return DocumentDatatable::availableQueryFilters();
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

        $this->applyLinks($dataTable, self::SHOW_URL_ROUTE, 'documents_');

        $dataTable->filterColumn('owner', function ($query, $keyword) {
            DataTableHelper::queryOwner($query, $keyword,'documents');
        });

        $dataTable->filterColumn('created_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('documents.created_at', array($dates[0], $dates[1]));
            }
        });
        $dataTable->filterColumn('updated_at', function ($query, $keyword) {
            $dates = DataTableHelper::getDatesForFilter($keyword);

            if ($dates != null) {
                $query->whereBetween('documents.updated_at', array($dates[0], $dates[1]));
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
    public function query(Document $model)
    {
        $query = $model->newQuery()
            ->with('owner')
            ->leftJoin('documents_dict_category','documents.document_category_id','=','documents_dict_category.id')
            ->leftJoin('documents_dict_status','documents.document_status_id','=','documents_dict_status.id')
            ->leftJoin('documents_dict_type','documents.document_type_id','=','documents_dict_type.id')
            ->select(
                'documents.*',
                'documents_dict_category.name as category',
                'documents_dict_status.name as status',
                'documents_dict_type.name as type'
            );

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
            ->setTableId('AccountDocumentsDatatable'.$this->tableSuffix)
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

        $columns = DocumentDatatable::availableColumns();

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
