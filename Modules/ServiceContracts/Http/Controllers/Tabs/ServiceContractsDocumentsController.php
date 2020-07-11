<?php

namespace Modules\ServiceContracts\Http\Controllers\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Documents\Entities\Document;
use Modules\Leads\Datatables\Tabs\LeadCampaignsDatatable;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\ServiceContracts\Datatables\Tabs\ServiceContractsDocumentsDatatable;
use Modules\ServiceContracts\Entities\ServiceContract;

/**
 * Class ServiceContractsDocumentsController
 * @package Modules\ServiceContracts\Http\Controllers\Tabs
 */
class ServiceContractsDocumentsController extends ModuleCrudRelationController
{
    protected $datatable = ServiceContractsDocumentsDatatable::class;

    protected $ownerModel = ServiceContract::class;

    protected $relationModel = Document::class;

    protected $ownerModuleName = 'servicecontracts';

    protected $relatedModuleName = 'documents';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'documents';

    protected $whereCondition = 'documents.id';
}
