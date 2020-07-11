<?php

namespace Modules\Leads\Http\Controllers\Tabs;

use Modules\Documents\Entities\Document;
use Modules\Leads\Datatables\Tabs\LeadDocumentsDatatable;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class LeadDocumentsController
 * @package Modules\Leads\Http\Controllers
 */
class LeadDocumentsController extends ModuleCrudRelationController
{
    protected $datatable = LeadDocumentsDatatable::class;

    protected $ownerModel = Lead::class;

    protected $relationModel = Document::class;

    protected $ownerModuleName = 'leads';

    protected $relatedModuleName = 'documents';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'documents';

    protected $whereCondition = 'documents.id';
}
